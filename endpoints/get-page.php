<?php

/*
	AJAX requests to this file are used to dynamically load content.
*/
require_once "includes/env.php";
require_once "includes/util.php";

// TODO: SANITIZE?
$page = Util::GetRequestParameter("value");
require_once "$page";