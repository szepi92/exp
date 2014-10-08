<?php

// TODO: These should all be wrapped in an object!!!

// Default environment variables
date_default_timezone_set('EST');

$DEBUG = true;
$VALIDATE_DATA = true;	// this should be turned on in production. we turn it off to make testing easier
$AUTO_UPDATE_DB = true;	// NOTE: THIS MAKES EVERYTHING SLOW. BUT MAKES EVERYTHING MAGICAL (see db.php)
$ROOT = '/exp';
$DB_TYPE = 'sqlite3';

// Things that differ depending on whether we are local or not
//if ($_SERVER['SERVER_NAME'] == 'localhost') {
//	$DEBUG = true;
//} else {
//	$DEBUG = false;
//}

$ABS_ROOT = "http://" . $_SERVER['HTTP_HOST'] . $ROOT;
