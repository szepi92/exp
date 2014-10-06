<?php
require_once 'includes/db.php';
require_once 'includes/language.php';
require_once 'includes/image.php';
require_once 'includes/util.php';

/*
Defines the Quiz class. Quizzes are repeatable,
so they are not linked to any particular user or sitting.
They define an ordering of questions.
*/

class Quiz extends DBObject {
	const NUMBER_OF_QUESTIONS = 250;		// The default number of questions per language
	const TABLE_NAME = "Quizzes";

	protected $languages = array();			// array of Language objects
	protected $questions_per_language = 0;	// number of questions per language
	protected $questions = array();			// $questions[i][j] = jth question of ith language)
	protected $creator = "";				// user id of the person who created this quiz
	protected $creation_time = 0;			// when was this quiz created
	
	public function __construct($q_per_lang, $languages, $user) {
		// Do the parent (DBObject) stuff
		$private_vars = array_keys(get_class_vars(__CLASS__));
		$columns = array_map(array('Util','MakeTitleCase'), $private_vars);
		parent::__construct(self::TABLE_NAME, $columns, $private_vars);		
		
		$this->languages = $languages;
		$this->questions_per_language = $q_per_lang;
		$this->creator = $user->id();
		$this->creation_time = time();
	}
	
	public function languages() {
		return $this->languages;
	}
	
	public function questionsPerLang() {
		return $this->questions_per_language;
	}
	
	public function totalQuestions() {
		$num_languages = count($this->languages);
		$total = $this->questions_per_language * $num_languages;
		return $total;
	}
	
	// Scans a directory for all images
	// Grabs a random set of images to be used as questions
	// NOTE: This may modify (decrease) "$this->questions_per_language" if there aren't enough files
	public function initFromDirectory($path) {
		// Get set of files (and shuffle them)
		$files = Image::ListAll($path);
		shuffle($files);
		
		// Reduce $this->questions_per_language if the number of images is too small
		$total_questions = min($this->totalQuestions(), count($files));
		
		$num_languages = count($this->languages);
		$questions_per_language = 0;
		if ($num_languages) {
			$questions_per_language = intval($total_questions / $num_languages);
		}
		$this->questions_per_language = $questions_per_language;
		
		if ($this->questions_per_language <= 0) {
			throw new Exception("Need at least 2 images in the image directory.");
		}
		
		// Construct the questions for each language
		$offset = 0;
		$this->questions = array();
		for ($i = 0; $i < $num_languages; ++$i) {
			$tmp = array_slice($files, $offset, $questions_per_language);
			$offset += $questions_per_language;
			$this->questions[] = $tmp;
		}
	}
	
	
}