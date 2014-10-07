<?php
require_once 'includes/db.php';

class Language extends DBObject {
	public $code;	// a short code for the language (e.g. "fr" or "ch")
	public $name;	// the language (full) name (e.g.: "French" or "Chinese")
	
	public function __construct($name, $code=null) {
		$this->name = ucfirst($name);
		if (empty($code)) $code = strtolower(substr($name,0,2));	// HACK
		$this->code = $code;
	}
	
	// Often, we don't care about the code
	public function __toString() {
		return $this->name;
	}
	
	// Parse something that would be returned from the database
	static public function FromJSON($obj) {
		if (is_string($obj)) $obj = json_decode($obj);
		return new Language($obj->name, $obj->code);
	}
	
	static public function FromJSONArray($array) {
		if (is_string($array)) $array = json_decode($array);
		return array_map(array(__CLASS__,'FromJSON'), $array);
	}
}