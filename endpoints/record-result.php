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

// Get the session from the request
// This will define the $quiz_session, $user, and $quiz
$got_session = include_once 'endpoints/session-handler.php'; 
if (!$got_session || !isset($quiz_session)) {
	DoError(ErrorMessages::BAD_SESSION);
}

die("\"Hello from record-result\"");

// TODO: This page doesn't do much yet

// SEE ABOVE FOR DESCRIPTIONS OF THESE
$type = Util::GetRequestParameter("type");
$timestamp = Util::GetRequestParameter("timestamp");
$value = Util::GetRequestParameter("value");
$nonce = Util::GetRequestParameter("nonce");
$language = Util::GetRequestParameter("language");
$question = Util::GetRequestParameter("question");

