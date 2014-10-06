<?
require_once "includes/env.php";
require_once "includes/db.php";
require_once "includes/error.php";
require_once "includes/quiz.php";
require_once "includes/user.php";
require_once "includes/language.php";
require_once "includes/image.php";
require_once "includes/quiz-session.php";

/*
When this page is hit, it expects information to be in the $_POST variable.
It will validate the info and redirect to instructions.php if necessary
*/

if ($_SERVER["REQUEST_METHOD"] != "POST") return;

function sanitize($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$first_name      = sanitize($_POST['FirstName']);
$last_name       = sanitize($_POST['LastName']);
$email           = sanitize($_POST['Email']);
$birthday        = sanitize($_POST['Birthday']);
$country         = sanitize($_POST['Country']);
$relocation      = sanitize($_POST['Relocation']);
$first_language  = sanitize($_POST['FirstLanguage']);
$second_language = sanitize($_POST['SecondLanguage']);

$ERROR = null;

// The dates
$birthday = strtotime($birthday);
$relocation = strtotime($relocation);

// The languages
$first_language  = new Language($first_language);
$second_language = new Language($second_language);
$languages       = array($first_language, $second_language);

$num_questions   = Quiz::NUMBER_OF_QUESTIONS;

// Create the Quiz, the User, and the Session
try {
	$quiz = new Quiz($num_questions, $languages);	// Create a blank quiz
	$user = new User($first_name, $last_name, $email, $birthday, $country, $relocation, $languages);
	$quiz_session = new QuizSession($quiz, $user);
} catch (Exception $e) {
	$ERROR = new Error($e, ErrorMessages::BAD_DATA);
	return;
}

// Initialize the quiz
try {
	$image_dir = Image::DEFAULT_DIRECTORY;
	$quiz->initFromDirectory($image_dir);
} catch (Exception $e) {
	$ERROR = new Error($e, ErrorMessages::UNKNOWN_ERROR);
	return;
}

// Save them to the database
try {
	$quiz->save();
	$user->save();
	$quiz_session->save();
} catch(Exception $e) {
	$ERROR = new Error($e, ErrorMessages::CONNECTION_ERROR);
	return;
}

/// TODO: We need to email the session id to the user

// Now that we're done, redirect the user
$url = "$ABS_ROOT/instructions.php?session=" . $quiz_session->id();
header("Location: $url");
die();














