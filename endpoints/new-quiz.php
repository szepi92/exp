<?
require_once "includes/env.php";
require_once "includes/db.php";
require_once "includes/error.php";
require_once "includes/quiz.php";
require_once "includes/user.php";
require_once "includes/language.php";
require_once "includes/image.php";
require_once "includes/quiz-session.php";
require_once "includes/util.php";

/*
This is responsible for creating the quiz,user, and session objects.
It also inserts them into the back-end (database).

When this page is hit, it expects information to be in the $_POST variable.
It will validate the info and redirect to instructions.php once complete.
*/

// Extract the data
$first_name      = Util::GetRequestParameter('FirstName');
$last_name       = Util::GetRequestParameter('LastName');
$email           = Util::GetRequestParameter('Email');
$birthday        = Util::GetRequestParameter('Birthday');
$country         = Util::GetRequestParameter('Country');
$relocation      = Util::GetRequestParameter('Relocation');
$first_language  = Util::GetRequestParameter('FirstLanguage');
$second_language = Util::GetRequestParameter('SecondLanguage');

// This will be returned to the front-end if necessary
$ERROR = null;

// Only continue if we are "POST"-ing
if ($_SERVER["REQUEST_METHOD"] != "POST") return;

// Validation of data
if ($VALIDATE_DATA) {
	// Check for empty data
	if (empty($first_name)) return $ERROR = new Error(ErrorMessages::BAD_DATA, "First Name is required.");
	if (empty($last_name)) return $ERROR = new Error(ErrorMessages::BAD_DATA, "Last Name is required.");
	if (empty($country)) return $ERROR = new Error(ErrorMessages::BAD_DATA, "Country of Origin is required.");
	if (empty($email)) return $ERROR = new Error(ErrorMessages::BAD_DATA, "E-Mail Address is required.");
	if (empty($first_language)) return $ERROR = new Error(ErrorMessages::BAD_DATA, "First Language is required.");
	if (empty($second_language)) return $ERROR = new Error(ErrorMessages::BAD_DATA, "Second Language is required.");

	// Check the email address to avoid hassle later
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	return $ERROR = new Error(ErrorMessages::BAD_DATA, "Please specify a valid E-Mail Address.");
}

// Further parse the dates (into UNIX time stamps)
$birthday = strtotime($birthday);
$relocation = strtotime($relocation);

// Further parse the languages (into Language objects)
$first_language  = new Language($first_language);
$second_language = new Language($second_language);
$languages       = array($first_language, $second_language);

$num_questions   = Quiz::NUMBER_OF_QUESTIONS;

// Create the Quiz, the User, and the Session
try {
	$user = new User($first_name, $last_name, $email, $birthday, $country, $relocation, $languages);
	$quiz = new Quiz($num_questions, $languages, $user);	// Create a blank quiz
	$quiz_session = new QuizSession($quiz, $user);
} catch (Exception $e) {
	$ERROR = new Error($e, ErrorMessages::BAD_DATA);
	return;
}

// Initialize the quiz (questions)
try {
	$image_dir = Image::DEFAULT_DIRECTORY;
	$quiz->initFromDirectory($image_dir);
} catch (Exception $e) {
	$ERROR = new Error($e, ErrorMessages::UNKNOWN_ERROR);
	return;
}

// Save them to the database
$good = true;
$conn = null;
try {
	$conn = DB::Connect();	// connect to default database
	$conn->beginTransaction();
		$good = $good && $quiz->insert($conn);
		$good = $good && $user->insert($conn);
		$good = $good && $quiz_session->insert($conn);
		if (!$good) throw new Exception(ErrorMessages::BAD_DB, "Could not define one or more objects (quiz, user, session).");
	$conn->commit();
} catch(Exception $e) {
	if ($conn != null) $conn->rollBack();
	$ERROR = new Error($e, ErrorMessages::CONNECTION_ERROR);
	return;
}

/// TODO: We need to email the session id to the user

// Now that we're done, redirect the user
$url = "$ABS_ROOT/instructions.php?session=" . $quiz_session->id();
header("Location: $url");
die();














