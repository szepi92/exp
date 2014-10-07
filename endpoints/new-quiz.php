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

// Only continue if we are "POST"-ing
if ($_SERVER["REQUEST_METHOD"] != "POST") return;


// Extract the data
$first_name      = Util::Sanitize($_POST['FirstName']);
$last_name       = Util::Sanitize($_POST['LastName']);
$email           = Util::Sanitize($_POST['Email']);
$birthday        = Util::Sanitize($_POST['Birthday']);
$country         = Util::Sanitize($_POST['Country']);
$relocation      = Util::Sanitize($_POST['Relocation']);
$first_language  = Util::Sanitize($_POST['FirstLanguage']);
$second_language = Util::Sanitize($_POST['SecondLanguage']);

// This will be returned to the front-end if necessary
$ERROR = null;

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
		if (!$good) throw new Exception("One or more INSERT queries failed quietly.");
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














