<?php
require_once 'includes/db.php';

class Language extends DBObject {
	public $code;	// a short code for the language (e.g. "fr" or "ch")
	public $name;	// the language (full) name (e.g.: "French" or "Chinese")
	
	public function __construct($name, $code=null) {
		$this->name = $name;
		if (empty($code)) $code = strtolower(substr($name,0,2));	// HACK
		$this->code = $code;
	}
	
	// Often, we don't care about the code
	public function __toString() {
		return $this->name;
	}
}