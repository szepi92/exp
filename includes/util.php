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
}