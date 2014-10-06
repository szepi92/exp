<?php

// Defines an abstraction around the databases

class PDOConnector {
	public $conn_string = "";
	public $user_name = "";
	public $password = "";
	
	function __construct($c,$u,$p) {
		$this->conn_string = $c;
		$this->user_name = $u;
		$this->password = $p;
	}
}

$sqlite3_connector = new PDOConnector(
	"sqlite:../db/db.sqlite3",
	NULL, NULL
);

// TODO: Enable mysql
$mysql_connector = new PDOConnector(
	'mysql:host=localhost;dbname=exp',
	'php_user',
	'98fr3jfj328fj382hf3j09rf80934j9f3hf93jf9jf94'
);

function DBConnect() {
	global $sqlite3_connector, $mysql_connector;
	
	$DB_CONNECTOR = NULL;
	if ($_SERVER['SERVER_NAME'] == 'localhost') {
		$DB_CONNECTOR = $sqlite3_connector;
	} else {
		$DB_CONNECTOR = $mysql_connector;
	}
	
	$conn = new PDO(
		$DB_CONNECTOR->conn_string,
		$DB_CONNECTOR->user_name,
		$DB_CONNECTOR->password);

	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $conn;
}

// A generic object that can be saved to the db
// Each subclass of this roughly corresponds to a table in the db
// Instances of the subclasses correspond to rows of the table
class DBObject {
}



