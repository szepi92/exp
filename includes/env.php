<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
	$ROOT = '/exp';
} else {
	$ROOT = '/';
}

$ABS_ROOT = $_SERVER['HTTP_HOST'] . $ROOT;
