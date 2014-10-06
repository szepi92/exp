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
}