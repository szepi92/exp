<?php
class ErrorMessages {
	const BAD_DATA			= "The data entered was invalid.";
	const UNKNOWN_ERROR		= "There was an issue while processing the request. Please try again.";
	const CONNECTION_ERROR	= "We could not connect to the server at this time. Please try again later.";
	const BAD_SESSION       = "Invalid session id.";
	const TIMEOUT	        = "Timeout while connecting to the server. Please try again.";
	const BAD_TYPE			= "Invalid 'type' parameter given.";
}

class Error {
	public $exception = null;
	public $friendly_message = "";
	
	public function __construct($exception, $friendly=null) {
		if (!isset($friendly) || $friendly == null) {
			// Overload (just a message)
			$this->exception = new Exception($exception);
			$this->friendly_message = $exception;
		} else {
			$this->exception = $exception;
			$this->friendly_message = $friendly;
		}
	}
}