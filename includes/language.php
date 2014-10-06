<?php
require_once 'includes/db.php';

class Language extends DBObject {
	public $code;
	public $name;
	
	public function __construct($name, $code=null) {
		$this->name = $name;
		if (empty($code)) $code = substr($name,0,2);
		$this->code = $code;
	}
}