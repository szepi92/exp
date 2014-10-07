<?

/*	This file acts as a generic API end-point for ajax calls.
	It is the leeway to the endpoints folder.
	For now, it only uses "record-result.php"
*/

require_once 'includes/env.php';
require_once 'includes/util.php';
require_once 'includes/error.php';

// Will return json
header('Content-Type: application/json');

function DoError($error) {
	global $DEBUG;
	
	$response = array(
		"status" => "error",
		"message" => $error->friendly_message
	);
	
	if ($DEBUG) {
		$response["exception"] = $error->exception->getMessage();
	}
	
	echo json_encode($response);
	die();
}

function DoResponse($msg="") {
	global $DEBUG;
	
	$response = array(
		"status" => "ok",
		"message" => $msg
	);
	
	echo json_encode($response);
	die();
}

class ActionTypes {	// Enum
	const PROCEED_TO_QUIZ = "proceed-to-quiz";
	const RECORD_RESULT = "record-result";
};

try {
	$type = Util::GetRequestParameter("type");
	if (empty($type)) throw new Exception(ErrorMessages::BAD_TYPE);
	
	// The end-points. Should only pass through one of them
	$result = false;
	if ($type == ActionTypes::PROCEED_TO_QUIZ)
		$result = include_once 'endpoints/continue-quiz.php';
	else if ($type == ActionTypes::RECORD_RESULT)
		$result = include_once 'endpoints/record-result.php';
	else
		throw new Exception(ErrorMessages::BAD_TYPE);
	
	// Return a result
	if ($result INSTANCEOF Error) {
		return DoError($result);
	} else if (!$result) {
		throw new Exception($result);
	} else {
		return DoResponse($result);
	}
} catch(Exception $e) {
	return DoError(new Error($e, ErrorMessages::UNKNOWN_ERROR));
}





