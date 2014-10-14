<?php

/*
	This file is responsible for collecting and formatting the set of results
	for a given a quiz-session. These are then passed to the front-end.
*/
require_once "includes/env.php";
require_once "includes/quiz-session.php";
require_once "includes/quiz.php";
require_once "includes/user.php";
require_once "includes/error.php";
require_once "includes/util.php";
require_once "includes/result.php";
require_once "includes/colour.php";
require_once "includes/algo.php";

// Get the session from the request
// This will define the $quiz_session, $user, and $quiz
$got_session = include_once 'endpoints/session-handler.php'; 
if (!$got_session || !isset($quiz_session)) {
	return false;
}

// Get back all the results from the db
$raw_results = Result::LookupAll($quiz_session->id());
$results_by_language = array();
foreach ($quiz->languages() as $language) {
	$results_by_language[] = (object)array(
		"language" => $language,
		"data" => array(),
		"average_speed" => 0,
		"slowest_speed" => 0,
		"slowest_word" => "",
		"fastest_speed" => 0,
		"fastest_word" => ""
	);
}

foreach ($raw_results as $result) {
	$language_idx = $result->language();
	$question_idx = $result->question();
	$results_by_language[$language_idx]->data[$question_idx] = $result;	// push this question
}

// Other needed variables
$summary = array(
	"faster_language" => "",
	"fastest_word" => "",
	"slowest_word" => ""
);

return true;






