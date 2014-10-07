<?php

/*
 Include this on pages where you need to validate the session.
 If he user goes to some page (such as the "query" page) with a session id,
 we may have to redirect them depending on the status of the session
*/

require_once "includes/env.php";
require_once "includes/quiz-session.php";

// Helper functions
function GoToPage($page, $msg=null) {
	global $ABS_ROOT, $session_id;
	
	$file_name = $_SERVER['SCRIPT_FILENAME'];
	if (strpos($file_name, $page) !== false) return;
	
	$append = "?";
	$url = "$ABS_ROOT/$page";
	if (!empty($session_id)) { $url .= $append . "session=$session_id"; $append = '&'; }
	if (!empty($msg)) { $url .= $append . "msg=$msg"; $append = '&'; }
	
	header("Location: $url");
	die();
}
function GoToHomePage($msg=null) { GoToPage("index.php",$msg); }
function GoToInstructionsPage($msg=null) { GoToPage("instructions.php",$msg); }
function GoToNewLanguagePage($msg=null) { GoToNewLanguagePage("new-language.php",$msg); }
function GoToImagePage($msg=null) { GoToPage("image-query.php",$msg); }
function GoToResultsPage($msg=null) { GoToPage("results.php",$msg); }
////

$result = include_once 'session-handler.php'; // $user, $quiz, and $quiz_session are now accessible 
if ($result == false || !isset($quiz_session)) {
	return GoToHomePage("Invalid session id.");
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