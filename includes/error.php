<?php
class ErrorMessages {
	const BAD_DATA			= "The data entered was invalid.";
	const UNKNOWN_ERROR		= "There was an issue while processing the request. Please try again.";
	const CONNECTION_ERROR	= "We could not connect to the server at this time. Please try again later.";
}

class Error {
	public $exception = null;
	public $friendly_message = "";
	
	public function __construct($exception, $friendly) {
		$this->exception = $exception;
		$this->friendly_message = $friendly;
	}
}