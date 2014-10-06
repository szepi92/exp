<?php
require_once 'includes/db.php';
require_once 'includes/quiz.php';
require_once 'includes/user.php';

/*
Defines the Quiz Session
*/

class SessionStatus {
	const INVALID		= "invalid";
	const READY			= "ready";
	const IN_PROGRESS	= "in-progress";
	const LANGUAGE_DONE	= "language-done";
	const COMPLETE		= "complete";
}

class QuizSession extends DBObject {
	private $quiz = null;
	private $user = null;
	
	private $current_language = 0;
	private $current_question = 0;
	private $status = SessionStatus::INVALID;
	private $start_time = -1;
	
	public function __construct($quiz, $user) {
		$this->quiz = $quiz;
		$this->user = $user;
		$this->status = SessionStatus::READY;
	}
}