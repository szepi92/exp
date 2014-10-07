<?php
/* 
	Defines an abstraction around the databases
*/

require_once "includes/env.php";
require_once "includes/util.php";

// A class to wrap PDO connection strings
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


// Common PDO connections
// TODO: Implement MySQL
$sqlite3_connector = new PDOConnector("sqlite:db/db.sqlite3",NULL, NULL);
$mysql_connector = new PDOConnector(
	'mysql:host=ENTER_HOST_NAME_HERE;dbname=ENTER_DB_NAME_HERE',
	'ENTER_USER_NAME_HERE',
	'ENTER_PASSWORD_HERE'
);

// An abstraction over common database tasks (e.g.: connect)
// Picks a default connection-string depending on the environment
class DB {
	static public function Connect() {
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
}


// TODO: Does this work with MySQL?

// A generic object that can be saved to the db
// Each subclass of this roughly corresponds to a table in the db
// Instances of the subclasses correspond to rows of the table
class DBObject {
	private $table = "";
	private $columns = array();	// the array of columns (as named in the database / SQL)
	private $fields = array();	// a parallel array to $columns: which fields to get from the child object
	private $map = array();
	private $_id = "";
	
	// Provide the underlying table name and the set of relevant columns
	// $column_array is an array of SQL column names, e.g.: "(ID,Name,Text)"
	// $field_array is a parellel array of property/field names to extract, e.g.: "id, name, story_text"
	// This information will automatically be used to Create/Read/Update/Delete later
	protected function __construct($tbl, $column_array, $field_array) {
		$this->table = $tbl;
		$this->columns = $column_array;
		$this->fields = $field_array;
		$this->map = Util::MakeAssocArray($this->columns,$this->fields);
		$this->_id = uniqid();		// Every object in the database has a random hash
	}
	
	public function id() {
		return $this->_id;
	}
	
	// Smartly find out the column type (TEXT,INT,NUMERIC) of a value
	private function getFieldType($val) {
		$type = "TEXT";
		if (is_numeric($val)) {
			if (floatval($val) == intval($val)) $type = "INT";
			else $type = "NUMERIC";
		}
		return $type;
	}
	
	// Creates a table for this type of object (pass the connection object)
	protected function createTable($conn=null) {
		if ($conn == null) $conn = DB::Connect();	// provide a default connection if none given

		// Construct the "CREATE TABLE" query
		$CREATE_QUERY = "CREATE TABLE IF NOT EXISTS $this->table (\n ID TEXT PRIMARY KEY NOT NULL";
		foreach ($this->columns as $idx => $column) {
			$field = $this->fields[$idx];
			$type = $this->getFieldType($this->$field);
			$CREATE_QUERY .= ",\n $column $type";
		}
		$CREATE_QUERY .= "\n);";
		return $conn->exec($CREATE_QUERY);
	}
	
	// Try to alter the table so that the columns match for this type of object
	protected function alterTable($conn=null) {
		if ($conn == null) $conn = DB::Connect();	// provide a default connection if none given

		foreach ($this->columns as $idx => $column) {
			$field = $this->fields[$idx];
			$type = $this->getFieldType($this->$field);
			$ALTER_QUERY = "ALTER TABLE $this->table ADD $column $type";
			
			// HACK: Ignore existing columns
			// How? We ignore exceptions with phrase "duplicate" in them
			try{
				$conn->exec($ALTER_QUERY);
			} catch (PDOException $e) {
				if (strstr($e->getMessage(), "duplicate") === FALSE)
					throw $e;
			}
		}
	}
	
	// Insert this object as a row into the database
	public function insert($conn=null) {
		if ($conn == null) $conn = DB::Connect();	// provide a default connection if none given

		global $AUTO_UPDATE_DB;

		if (empty($this->columns) or empty($this->table)) {
			throw new Exception("Cannot call DB::insert without defining table name and columns");
		}
		
		// Check to create the table first
		// NOTE: This is like 3 or 4 times slower AT LEAST; and probably volatile
		// Turn off $AUTO_UPDATE_DB in production
		if ($AUTO_UPDATE_DB) {
			$this->createTable($conn);
			$this->alterTable($conn);
		}
		
		// Prepare to construct a sql query string from the current object
		$column_list = implode(',',$this->columns) . ",ID";
		$var_list = ':' . implode(',:', $this->fields) . ",:id";
		$values = array();
		foreach ($this->fields as $field) {
			$v = $this->$field;
			if (is_object($v) or is_array($v))
				$values[$field] = json_encode($v);
			else
				$values[$field] = $v;
		}
		$values['id'] = $this->id();
		
		$query_string = "INSERT INTO $this->table ($column_list) VALUES ($var_list)";
		
		// Execute the query
		$stmt = $conn->prepare($query_string);
		$stmt->execute($values);
		
		// Returns true if the insertion was successful
		return (bool)$stmt->rowCount();
	}
	
	// This gets (overwrites) an object from the table given the id
	public function get($conn=null) {
		if ($conn == null) $conn = DB::Connect();	// provide a default connection if none given
		
		// Execute the query
		$query_string = "SELECT * FROM $this->table WHERE ID = :id";
		$stmt = $conn->prepare($query_string);
		$stmt->execute(array('id' => $this->id()));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// Map the results from ColumnNames to object_fields
		if ($result === FALSE) return FALSE;
		foreach ($result as $column => $value) {
			if ($column == "ID") continue;
			$field = $this->map[$column];
			$this->$field = $value;
		}
		return true;
	}
	
	// If you construct with just an id, we should interpret this as a "get" request
	// and read it from the database.
	// ASSUMES the DBObject::__constructor has already been called
	protected function _constructFromId($id) {
		// HACK: This function relies on the details of the get() function
		$this->_id = $id;		// first overwrite the id
		return $this->get();	// then overwrite everything else
	}
}



