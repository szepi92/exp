<?php
class ErrorMessages {
	// Common error messages (IN ALPHABETIC ORDER)
	
	const BAD_DATA			= "The data entered was invalid.";
	const BAD_DB			= "Error while reading or writing database.";
	const BAD_SESSION       = "Invalid session or session id.";
	const BAD_TYPE			= "Invalid 'type' parameter given.";
	const CONNECTION_ERROR	= "We could not connect to the server at this time. Please try again later.";
	const CORRUPT_DATA		= "The database is out of sync.";
	const NOT_IMPLEMENTED	= "This functionality is not yet implemented.";
	const TIMEOUT	        = "Timeout while connecting to the server. Please try again.";
	const UNKNOWN_ERROR		= "There was an issue while processing the request. Please try again.";
}

class Error {
	public $exception = null;
	public $friendly_message = "";
	
	public function __construct($exception, $friendly=null) {
		if (!isset($friendly) || $friendly == null) {
			// Overload (just a message)
			$this->exception = new Exception($exception);
			$this->friendly_message = $exception;
		} else if ($exception INSTANCEOF Exception) {
			$this->exception = $exception;
			$this->friendly_message = $friendly;
		} else {
			$this->exception = new Exception($exception);
			$this->friendly_message = $friendly;
		}
	}
}