<?php

/*
	TODO: This file is incomplete!!
	
	
	AJAX requests to this file are used to record results for quiz sessions.
	The parameters are given in the $_GET object as follows:
	
		-session: the usual, session id hash/string
		-timestamp: UNIX timestamp of event (should not be too long ago)
		-value: arbitrary value (usually corresponds to reaction-time)
		-nonce: a string for security purposes (used for validation / sanity check)
		-language: the language index (in {0,1} usually)
		-question: the question index (in {0,1,2,...,249} usually)
*/
require_once "includes/env.php";
require_once "includes/quiz-session.php";
require_once "includes/quiz.php";
require_once "includes/user.php";
require_once "includes/error.php";
require_once "includes/util.php";
require_once "includes/result.php";

// Get the session from the request
// This will define the $quiz_session, $user, and $quiz
$got_session = include_once 'endpoints/session-handler.php'; 
if (!$got_session || !isset($quiz_session)) {
	return new Error(ErrorMessages::BAD_SESSION);
}

// SEE ABOVE FOR DESCRIPTIONS OF THESE
$timestamp = Util::GetRequestParameter("timestamp");
$value = Util::GetRequestParameter("value");
$nonce = Util::GetRequestParameter("nonce");
$language = Util::GetRequestParameter("language");
$question = Util::GetRequestParameter("question");

// Verify that the session is evening running
$status = $quiz_session->status();
if ($status != SessionStatus::IN_PROGRESS) {
	return new Error(ErrorMessages::BAD_SESSION, "Existing session is not in-progress.");
}

// Verify that the data is all there
if ($language == null || $quiz_session->currentLanguage() != $language)
	return new Error(ErrorMessages::BAD_DATA, "Missing or incorrect 'language' parameter");
if ($question == null || $quiz_session->currentQuestion() != $question)
	return new Error(ErrorMessages::BAD_DATA, "Missing or incorrect 'question' parameter");
if ($value == null || !is_numeric($value))
	return new Error(ErrorMessages::BAD_DATA, "Missing or incorrect 'value' parameter");
	
// Make sure a result doesn't already exist
try {
	$result = Result::Lookup($quiz_session->id(), $language, $question);
	if ($result !== false) 
		return new Error(ErrorMessages::BAD_DATA, "Attempting to record duplicate result");
} catch(Exception $e) {
	return new Error($e, ErrorMessages::UNKNOWN_ERROR);
}

// We do the following as a transaction.
// There are two steps: Insert the result, and update the current quiz-session.
$conn = DB::Connect();
$conn->beginTransaction();

// Result is fresh, record it
try {
	// Note: "Results" are standalone objects that add themselves to the database
	// TODO: This might be a design flaw
	$result = new Result($quiz_session->id(), $language, $question, $value);
	$good = $result->insert($conn);
	
	// Note: Implicit $conn->rollBack() here if $conn goes out of scope
	if (!$good) return new Error(ErrorMessages::BAD_DB, "Error while saving the result");
} catch (Exception $e) {
	$conn->rollBack();
	return new Error($e, "Error while saving the result.");
}

// Now update the quiz session (move onto next question)
try {
	$num_questions = $quiz->questionsPerLang();
	$num_languages = $quiz->numLanguages();
	
	// Check all the cases (done question, done language, done quiz, etc.)
	if ($question == $num_questions - 1) {	// Do something special (no more questions for this language)
		if ($language == $num_languages - 1) {	// done quiz
			$quiz_session->setStatus(SessionStatus::COMPLETE);
		} else if ($language < $num_languages - 1)  { // done the language
			$quiz_session->setStatus(SessionStatus::LANGUAGE_DONE);
			$quiz_session->setLanguage($language + 1);
			$quiz_session->setQuestion(0);
		} else {
			// THIS SHOULD NEVER HAPPEN :(
			// Invalid language
			return new Error(ErrorMessages::CORRUPT_DATA, "Could not find the language specified.");
		}
	} else if ($question < $num_questions - 1) { // Still have more to go
		$quiz_session->setQuestion($question + 1);
	} else {
		// THIS SHOULD NEVER HAPPEN :(
		// Invalid question
		return new Error(ErrorMessages::CORRUPT_DATA, "Could not find the question specified.");
	}
	
	// Now we save (UPDATE) to the database
	$good = $quiz_session->save($conn);
	
	// Note: Implicit $conn->rollBack() here if $conn goes out of scope
	if (!$good) throw new Exception(ErrorMessages::BAD_DB);
} catch (Exception $e) {
	$conn->rollBack();
	return new Error($e, "Error while updating the session.");
}

// All done
if ($conn->commit()) return "Success! Result recorded and session updated.";
else return new Error(ErrorMessages::BAD_DB);