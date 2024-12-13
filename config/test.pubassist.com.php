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
$sslCertPath = "/etc/ssl/certs/ca-certificates.crt";
// Using a LetsEncrypt certificate that lies in its own folder.
#$sslCertPath = "/etc/letsencrypt/live/app.ccgopvt.org/fullchain.pem";

// It seems that the cacert.pem certificate is the only one that works.
$sslCertPath = "/etc/ssl/private/cacert.pem" ;

// Default location for the REST API modules.
#$RESTroot = "https://" . $_SERVER['SERVER_NAME'] . "/common/rest/";
$protocol = $_SERVER['PROTOCOL'] = 
			isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';

// Set the REST root to the development server--rather than the testing server.
#$RESTroot = $protocol . $_SERVER['SERVER_NAME'] . "/common/rest/";
$RESTroot = $protocol . "dev.pubassist.com/common/rest/";


// Database DSN.
$DSN = 'pubassistDemo';

