<?php

/*
 Include this on pages where you need to validate the session.
 If he user goes to some page (such as the "query" page) with a session id,
 we may have to redirect them depending on the status of the session
*/

require_once "includes/env.php";
require_once "includes/quiz-session.php";

const SESSION_TAG = 'session';

// Helper functions
function GoToPage($page, $msg=null) {
	global $ABS_ROOT, $session_id;
	
	$file_name = $_SERVER['SCRIPT_FILENAME'];
	if (strpos($file_name, $page) !== false) return;
	
	$url = "$ABS_ROOT/$page";
	if (!empty($session_id)) $url .= "?session=$session_id";
	if (!empty($msg)) $url .= "?msg=$msg";
	
	header("Location: $url");
	die();
}
function GoToHomePage($msg=null) { GoToPage("index.php",$msg); }
function GoToInstructionsPage($msg=null) { GoToPage("instructions.php",$msg); }
function GoToNewLanguagePage($msg=null) { GoToNewLanguagePage("new-language.php",$msg); }
function GoToImagePage($msg=null) { GoToPage("image-query.php",$msg); }
function GoToResultsPage($msg=null) { GoToPage("results.php",$msg); }
////


// Checks for session id existence
// These will break the script if session_id doesn't exist
if ($_SERVER["REQUEST_METHOD"] != "GET") return GoToHomePage();
if (!isset($_GET[SESSION_TAG]) || empty($_GET[SESSION_TAG])) return GoToHomePage();

// Now recover the actual quiz session (from the database)
$quiz_session = null;
try {
	$session_id = $_GET[SESSION_TAG];
	$quiz_session = new QuizSession($session_id);
} catch (Exception $e) {
	GoToHomePage("Invalid session id.");
}

// Now we can check the quiz-session status to see where we should redirect to
switch ($quiz_session->status()) {
	case SessionStatus::READY:
		GoToInstructionsPage();
		break;

	case SessionStatus::IN_PROGRESS:
		GoToImagePage();
		break;

	case SessionStatus::LANGUAGE_DONE:
		GoToNewLanguagePage();
		break;

	case SessionStatus::COMPLETE:
		GoToResultsPage();
		break;

	case SessionStatus::INVALID:
	default:
		return GoToHomePage("Invalid session id.");
		break;
}

// If you get here, it means the user is on the correct page

// Get the related user and quiz instances as well
$user = new User($quiz_session->userID());
$quiz = new Quiz($quiz_session->quizID());

// $user, $quiz, and $quiz_session are now accessible on the front-end


