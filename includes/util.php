<?php

/* Miscellaneous / useful utility functions */

class Util {

	// Takes "some_string" and returns "SomeString"
	static public function MakeTitleCase($str) {
		$matches = explode("_",$str);
		foreach ($matches as &$match) {
			$match = ucfirst($match);
		}
		return implode("",$matches);
	}
	
	static public function MakeAssocArray($keys, $values) {
		$ans = array();
		foreach ($keys as $idx => $key) {
			$ans[$key] = $values[$idx];
		}
		return $ans;
	}
	
	// Remove obvious special characters from user-generated input
	static public function Sanitize($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	
	// Get some parameter from the $_REQUEST
	static public function GetRequestParameter($key) {
		if (!isset($_REQUEST[$key]) || $_REQUEST[$key] == null) return null;
		return self::Sanitize($_REQUEST[$key]);
	}
}