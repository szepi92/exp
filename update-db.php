<?php
/*
Run this (from the browser) whenever you need to update the database
*/

require_once 'includes/db.php';
require_once 'includes/env.php';

header("Location: $ABS_ROOT");
die();
// RIGHT NOW YOU DONT DO ANYTHING!?

// TODO: This page does nothing right now
try {
	$conn = DB::Connect();
	

	$CREATE_TABLE = "";


	// Create the database
	echo $CREATE_TABLE;
	$res = $conn->query($CREATE_TABLE);
	if ($res === FALSE) {
		echo "Could not create database";
	} else {
		echo "SUCCESSFULLY CREATED DATABASE";
	}
	
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}