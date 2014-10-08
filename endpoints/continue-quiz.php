<?php

/*
	AJAX requests to this file are used to start and continue quizzes.
	Usually these are called from instructions pages or wherever.
	Users use this to signal that they want the quiz to begin.
	The parameters are given in the $_GET object as follows:
	
		-session: the usual, session id hash/string
		-timestamp: UNIX timestamp of event (should not be too long ago)
		-language: the language index (in {0,1} usually). This should be increasing over all calls
*/
require_once "includes/env.php";
require_once "includes/quiz-session.php";
require_once "includes/quiz.php";
require_once "includes/user.php";
require_once "includes/error.php";
require_once "includes/util.php";

// Get the session from the request
// This will define the $quiz_session, $user, and $quiz
$got_session = include_once 'endpoints/session-handler.php'; 
if (!$got_session || !isset($quiz_session) || $quiz_session->status() == SessionStatus::INVALID) {
	return new Error(ErrorMessages::BAD_SESSION);
}

$language = Util::GetRequestParameter("language");
$timestamp = Util::GetRequestParameter("timestamp");

$status = $quiz_session->status();

// Start first language
if ($status == SessionStatus::READY) {
	if (!isset($timestamp) || !isset($language) || $language != 0) {
		return new Error(ErrorMessages::BAD_DATA);
	}
	$quiz_session->start($timestamp);
	if ($quiz_session->save()) return "Quiz now started.";
	else return new Error(ErrorMessages::BAD_DB, "Could not start quiz.");
} else if ($status == SessionStatus::LANGUAGE_DONE) {
	$cur_language = $quiz_session->currentLanguage();
	if (!isset($language) || $language != $cur_language || $language >= count($quiz->languages())) {
		// Should be starting the next quiz (currentLanguage() will already be set)
		return new Error(ErrorMessages::BAD_DATA);
	}
	
	// These should already match
	//$quiz_session->setLanguage($language);
	$quiz_session->setQuestion(0);
	$quiz_session->setStatus(SessionStatus::IN_PROGRESS);
	if ($quiz_session->save()) return "Beginning the next language.";
	else return new Error(ErrorMessages::BAD_DB, "Could not continue quiz");
} else {
	// Hack (ish)
	return new Error("Session is currently " . $status . ".");
}














