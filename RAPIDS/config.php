<?php

/*
 *	config.php - These variables are configurtion properties of the
 *				web application.
 */

$eol = "<br/>\n";

// Start the session.
session_start();

// Default location for the REST API modules.
$protocol = $_SERVER['PROTOCOL'] = 
			isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';

$RESTroot = $protocol . $_SERVER['SERVER_NAME'] . "/common/rest/";

// Database DSN.
$DSN = 'pubassistDemo';

