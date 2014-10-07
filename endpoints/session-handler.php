<?php

/*
 Extracts $quiz_session information based on the "session" value
 provided in the $_REQUEST. Will also populate the variables: $quiz and $user.
 Returns true on success, false on fail.
 
 This is useful because most pages related to quiz-taking require a quiz-session.
 Include it freely anywhere.
*/

require_once "includes/env.php";
require_once "includes/quiz-session.php";
require_once "includes/quiz.php";
require_once "includes/user.php";

const SESSION_TAG = 'session';

if (!isset($_REQUEST[SESSION_TAG]) || empty($_REQUEST[SESSION_TAG])) return false;

try {
	$session_id = $_REQUEST[SESSION_TAG];
	$quiz_session = new QuizSession($session_id);
	
	if ($quiz_session->status() == SessionStatus::INVALID) {
		return false;
	}

	// Get the related user and quiz instances as well
	$user = new User($quiz_session->userID());
	$quiz = new Quiz($quiz_session->quizID());
	
	// Signal that it all worked
	return true;
} catch (Exception $e) {
	return false;
}