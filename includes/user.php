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
	
	public function __construct($first_name, $last_name, $email, $birthday, $country, $relocation, $languages) {
		$private_vars = array_keys(get_class_vars(__CLASS__));
		$columns = array_map(array('Util','MakeTitleCase'), $private_vars);
		parent::__construct(self::TABLE_NAME, $columns, $private_vars);
		
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;
		$this->birthday = $birthday;
		$this->country = $country;
		$this->relocation = $relocation;
		$this->languages = $languages;
	}
}