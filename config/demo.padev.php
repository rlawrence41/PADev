<?php

/*
 *	config.php - These variables are configurtion properties of the
 *				web application.
 */

$eol = "<br/>\n";
#echo "Executing the REAL config.php!" . $eol ;

// Start the session.
session_start();

// Point to a self-authorized SSL certificate for CURL.
#$sslCertPath = "/etc/apache2/ssl/vm.demo.pubassist.crt";

// If not self-authorized, set the ssl certificate path to NULL.
// See "CURL Configuration", May 20, 2020 in the diary.
$sslCertPath = null;

// Using a LetsEncrypt certificate that lies in its own folder.
$sslCertPath = "/etc/letsencrypt/live/dev.pubassist.com/fullchain.pem";

// It seems that the cacert.pem certificate is the only one that works.
#$sslCertPath = "/etc/ssl/private/cacert.pem" ;

// Default location for the REST API modules.
#$RESTroot = "https://" . $_SERVER['SERVER_NAME'] . "/common/rest/";
$protocol = $_SERVER['PROTOCOL'] = 
			isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';

$RESTroot = $protocol . $_SERVER['SERVER_NAME'] . "/common/rest/";


// Database DSN.
$DSN = 'pubassistDemo';

