<?php
require_once 'includes/db.php';
require_once 'includes/language.php';

/*
Defines the User class. Users are people.
*/

class User extends DBObject {
	const TABLE_NAME = "Users";
	
	protected $first_name		= "";
	protected $last_name		= "";
	protected $email			= "";
	protected $birthday			= 0;	// UNIX time stamp
	protected $country			= "";
	protected $relocation_date	= 0;	// UNIX time stamp
	protected $languages		= array();
	
	public function __construct($first_name, $last_name=null, $email=null, $birthday=null, $country=null, $relocation=null, $languages=null) {
		// Do parent constructor stuff first
		$private_vars = array_keys(get_class_vars(__CLASS__));
		$columns = array_map(array('Util','MakeTitleCase'), $private_vars);
		parent::__construct(self::TABLE_NAME, $columns, $private_vars);
		
		// Overloaded constructor (get with id) if necessary
		if (func_num_args() == 1 && is_string($first_name)) {
			$this->_constructFromId($first_name);
			$this->languages = Language::FromJSONArray($this->languages);
		} else {
			$this->first_name = $first_name;
			$this->last_name = $last_name;
			$this->email = $email;
			$this->birthday = $birthday;
			$this->country = $country;
			$this->relocation = $relocation;
			$this->languages = $languages;
		}
	}
}