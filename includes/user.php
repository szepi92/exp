<?php
require_once 'includes/db.php';
require_once 'includes/language.php';

/*
Defines the User class
*/

class User extends DBObject {
	private $first_name			= "";
	private $last_name			= "";
	private $email				= "";
	private $birthday			= 0;	// UNIX time stamp
	private $country			= "";
	private $relocation_date	= 0;	// UNIX time stamp
	private $languages			= array();
	
	public function __construct($first_name, $last_name, $email, $birthday, $country, $relocation, $languages) {
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;
		$this->birthday = $birthday;
		$this->country = $country;
		$this->relocation = $relocation;
		$this->languages = $languages;
	}
}