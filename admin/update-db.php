<?php
/*
Run this (from the browser) whenever you need to update the database
*/

include_once '../includes/db.php';

// TODO: This page does nothing right now
try {
	$conn = DBConnect();
	
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}