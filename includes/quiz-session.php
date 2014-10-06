<?php
require_once 'includes/db.php';
require_once 'includes/quiz.php';
require_once 'includes/user.php';

/*
Defines the Quiz Session.
Definition: a particular sitting of a particular quiz for a particular user
*/

// Enum for session status
class SessionStatus {
	const INVALID		= "invalid";
	const READY			= "ready";
	const IN_PROGRESS	= "in-progress";
	const LANGUAGE_DONE	= "language-done";
	const COMPLETE		= "complete";
}

class QuizSession extends DBObject {
	const TABLE_NAME = "QuizSessions";
	
	protected $quiz_id = null;
	protected $user_id = null;
	
	protected $current_language = 0;
	protected $current_question = 0;
	protected $status = SessionStatus::INVALID;
	protected $start_time = 0;
	
	public function __construct($quiz, $user) {
		$this->quiz_id = $quiz->id();
		$this->user_id = $user->id();
		$this->status = SessionStatus::READY;
		
		// Do the parent (DBObject) stuff
		$private_vars = array_keys(get_class_vars(__CLASS__));
		$columns = array_map(array('Util','MakeTitleCase'), $private_vars);
		parent::__construct(self::TABLE_NAME, $columns, $private_vars);		
	}
}