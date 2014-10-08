<?php

// TODO: These should all be wrapped in an object!!!

date_default_timezone_set('EST');

$DEBUG = false;
$VALIDATE_DATA = true;	// this should be turned on in production. we turn it off to make testing easier
$AUTO_UPDATE_DB = true;	// NOTE: THIS MAKES EVERYTHING SLOW. BUT MAKES EVERYTHING MAGICAL (see db.php)

if ($_SERVER['SERVER_NAME'] == 'localhost') {
	$DEBUG = true;
	$ROOT = '/exp';
} else {
	$DEBUG = false;
	$ROOT = '/';
}

$ABS_ROOT = "http://" . $_SERVER['HTTP_HOST'] . $ROOT;
