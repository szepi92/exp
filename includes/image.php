<?php
require_once 'includes/db.php';

/*
Defines the Image class. Responsible for listing images in a directory and getting data
*/

class Image extends DBObject {
	const DEFAULT_DIRECTORY = 'images';
	
	private static function IsValidFile($fname) {
		if (empty($fname)) return false;
		if ($fname[0] == '.') return false;
		return true;
	}
	
	static public function ListAll($dir) {
		return array_filter(scandir($dir, array(__CLASS__, "IsValidFile")));
	}
}