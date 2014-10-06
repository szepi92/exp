<?php
require_once 'includes/db.php';
require_once 'includes/language.php';
require_once 'includes/image.php';

/*
Defines the Quiz class
*/

class Quiz extends DBObject {
	const NUMBER_OF_QUESTIONS = 50;		// The default number of questions per language

	private $languages = array();		// array of Language objects
	private $questions_per_lang = 0;	// number of questions per language
	private $questions = array();		// an array of arrays ($questions[i][j] = jth question of ith language)
	
	public function __construct($q_per_lang, $languages) {
		$this->languages = $languages;
		$this->questions_per_lang = $q_per_lang;
	}
	
	public function languages() {
		return $this->languages;
	}
	
	public function questionsPerLang() {
		return $this->questions_per_lang;
	}
	
	public function totalQuestions() {
		$num_languages = count($this->languages);
		$total = $this->questions_per_lang * $num_languages;
		return $total;
	}
	
	// Scans a directory for all images
	// Grabs a random set of images to be used as questions
	// NOTE: This may modify (decrease) "$this->questions_per_lang" if there aren't enough files
	public function initFromDirectory($path) {
		// Get set of files (and shuffle them)
		$files = Image::ListAll($path);
		shuffle($files);
		
		// Reduce $this->questions_per_lang if the number of images is too small
		$total_questions = min($this->totalQuestions(), count($files));
		$num_languages = count($this->languages);
		$questions_per_lang = 0;
		if ($num_languages) {
			$questions_per_lang = $total_questions / $num_languages;
		}
		$this->questions_per_lang = $questions_per_lang;
		
		// Construct the questions for each language
		$offset = 0;
		$this->questions = array();
		for ($i = 0; $i < $num_languages; ++$i) {
			$tmp = array_slice($files, $offset, $questions_per_lang);
			$offset += $questions_per_lang;
			$this->questions[] = $tmp;
		}
	}
	
	
}