<?php
require_once 'includes/db.php';
require_once 'includes/quiz.php';
require_once 'includes/user.php';
require_once 'includes/quiz-session.php';

/*
Defines the Result object, which is the recorded data for a single question
for a particular sitting/session.
*/

class Result extends DBObject {
	const TABLE_NAME = "Results";
	
	// Identifying information
	protected $quiz_session_id = null;
	protected $language = -1;
	protected $question = -1;
	
	// Recorded information
	protected $reaction_ms;	// milliseconds, reaction time
	protected $sound_data;	// TODO: Not used for now
	
	// All fields required
	public function __construct($session_id, $language, $question, $reaction_ms, $sound_data = "") {
		// Do the parent (DBObject) stuff
		$private_vars = array_keys(get_class_vars(__CLASS__));
		$columns = array_map(array('Util','MakeTitleCase'), $private_vars);
		parent::__construct(self::TABLE_NAME, $columns, $private_vars);	
		
		$this->quiz_session_id = $session_id;
		$this->language = $language;
		$this->question = $question;
		$this->reaction_ms = $reaction_ms;
		$this->sound_data = $sound_data;
	}
	
	// Basic setters
	public function question() {
		return $this->question;
	}
	public function language() {
		return $this->language;
	}
	public function reactionTime() {	// milliseconds
		return $this->reaction_ms;
	}
	
	// Check if a result already exists for this (session, language, question)
	// Returns the value associated with it.
	// TODO: Should return actual result object
	public static function LookUp($session_id, $language, $question, $conn=null) {
		if ($conn == null) $conn = DB::Connect();	// provide a default connection if none given
		
		// Construct the query
		$query_string = "SELECT * FROM " . self::TABLE_NAME . " WHERE " .
			"QuizSessionID = :session_id AND " .
			"Language = :language AND " .
			"Question = :question";
				
		// Construct the value map
		$values = array(
			"session_id" => $session_id,
			"language" => $language,
			"question" => $question
		);
		
		// Execute the query
		$stmt = $conn->prepare($query_string);
		$stmt->execute($values);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// Extract the return value
		if ($result === FALSE) return FALSE;
		$ans = $result["ReactionMs"];
		$stmt->closeCursor();
		return $ans;
	}
	
	public static function LookUpAll($session_id, $conn=null) {
		if ($conn == null) $conn = DB::Connect();
		
		// Construct the query
		$query_string = "SELECT * FROM " . self::TABLE_NAME . " WHERE QuizSessionID = :session_id";
		$values = array("session_id" => $session_id);
		
		// Execture the query
		$stmt = $conn->prepare($query_string);
		$stmt->execute($values);
		$results = $stmt->fetchAll();
		
		// Process the results (return array of Result object)
		$ans = array();
		foreach ($results as $result) {
			$ans[] = new Result($session_id, $result["Language"], $result["Question"], $result["ReactionMs"]);
		}
		
		return $ans;
	}
}