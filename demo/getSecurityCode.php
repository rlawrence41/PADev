<?php

/*
 *	getSecurityCode.php - This page is dedicated to the password reset process.  
 *				This procedure doesn't actually present a page.  It responds
 *				to a request to capture a user's authorization, and generate
 *				a security code.
 */
 
include_once ("includes.php");
include_once ("auth.class.php");

//  Set the REST API URL to obtain the user auth record.
$protocol = ($_SERVER['HTTPS']=="on") ? "https://" : "http://" ;
$url = $protocol . $_SERVER['SERVER_NAME'];
$url .= "/user/rest/user.php/user";

//	Pass along the query string.
$queryStr = $_SERVER['QUERY_STRING'];
$url .= "?" . $queryStr;

//	Submit the request.
$JSONstring = postToURL($url);

//	If we have an authorization
if (stripos($JSONstring, "CURL error:") == 0) {

	// The result should be JSON.
	$result = json_decode($JSONstring, true);

	// Capture JUST the authorization--NOT the count.
	$auth = $result[1][0];

	// Save the authorization to the session.
	$_SESSION['auth']=$auth;
	
	// Generate and save a security code to the session.
	$securityCode = bin2hex(random_bytes(4));
	$_SESSION['securityCode'] = $securityCode;
	$securityMsg = "Your security code is: " . $securityCode;

	// How should the security code be sent?
	if ($_GET['notify']=="email") {sendSCemail($securityCode);}
	if ($_GET['notify']=="text") {sendSCtext($securityCode);}
	
	// Respond with the JSON result.
	header('Content-Type: application/json');
	echo $JSONstring;

}
else {
	// I don't want to return a 200 status if no data was found.
	debug_msg("User was not found.", false, "getSecurityCode.php");
	header("HTTP/1.0 204 Not Found", true, 204);
	echo "No user was found.";
	return;
}

/*
 *	sendSCemail() -- Send the security code to the user via email.
 */
function sendSCemail($securityCode) {

 	// Send the security code to the user.
	$to = $_SESSION['auth']['email'];
	$subject = "Your security code from PubAssist...";
	$message = "<p>Your PubAssist security code is: <b>{$securityCode}</b></p>\n";
	$message .= "<p>This code will be valid only for your current session.</p>";
	$notification = sendMail($to, $subject, $message); 

}


/*
 *	sendSCtext() -- Send the security code to the user via SMS Text.
 */
function sendSCtext() {

 	// Send the security code to the user.
	$SMSdomain = "@txt.att.net";	// Clearly, this needs to be expanded.
	$to = stripPhone($_SESSION['auth']['phone']) . $SMSdomain ;
	$subject = "Your security code from PubAssist...";
	$message = "<p>Your PubAssist security code is: <b>{$securityCode}</b></p>\n";
	$message .= "<p>This code will be valid only for your current session.</p>";
	$notification = sendMail($to, $subject, $message); 
}
?>