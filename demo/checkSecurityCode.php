<?php

/*
 *	checkSecurityCode.php - This page is dedicated to the password reset process.  
 *				This procedure doesn't actually present a page.  It responds
 *				to a request to compare a user's submitted security code to one
 *				generated in a previous step.
 */
 
include_once ("includes.php");
include_once ("auth.class.php");

	// What is the security code submitted by the user?
	$column = $_GET['column'];
	$submittedCode = $column['securityCode'];
	
	// Does the submitted code match the generated code?
	$securityCode = trim($_SESSION['securityCode']);
	
#	$stringCompare = strcasecmp($submittedCode, $securityCode);
	
	if ($securityCode == $submittedCode) { echo "Success!" ; }
	else {
		header('X-PHP-Response-Code: 401', true, 401);
		$message = "Security code does not match. ";
#		$message .= "\nSecurityCode: " . $securityCode;
		$message .= "\nCode you entered: " . $submittedCode;
		echo $message;
	}

?>