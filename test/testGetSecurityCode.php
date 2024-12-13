<?php

/*
 *	getSecurityCode.php - This page is dedicated to the password reset process.  
 *				This procedure doesn't actually present a page.  It responds
 *				to a request to capture a user's authorization, and generate
 *				a security code.
 */
 
include_once ("includes.php");
include_once ("auth.class.php");

$HTMLresponse = "<h2>Response should show here after posting.</h2>";

//  Set the REST API URL to obtain the user auth record.
$url = "https://" . $_SERVER['SERVER_NAME'];
$url .= "/common/rest/user.php/user";

//	Pass along the query string.
$queryStr = $_SERVER['QUERY_STRING'];
$url .= "?" . $queryStr;

//	Submit the request.
$HTMLresponse = postToURL($url);

//	If we have an authorization
if (stripos($HTMLresponse, "CURL error:") == 0) {

	// The result should be JSON.
	$result = json_decode($HTMLresponse, true);
	// Capture JUST the authorization--NOT the count.
	$auth = $result[1][0];
	// Save the authorization to the session.
	$_SESSION['auth']=$auth;
	
	// Generate a security code.
	$securityCode = bin2hex(random_bytes(4));
	$_SESSION['securityCode'] = $securityCode;
	$securityMsg = "Your security code is: " . $securityCode;
	
 	// Send the security code to the user.
	$to = $_SESSION['auth']['email'];
	$subject = "Your security code from PubAssist...";
	$message = "<p>Your PubAssist security code is: <b>{$securityCode}</b></p>\n";
	$message .= "<p>This code will be valid only for your current session.</p>";

#	debug_msg($message, true, "getSecurityCode");
	$notification = sendMail($to, $subject, $message); 
}
?>


<html>
<body>
<h1>Get Security Code</h1>

<p>You shouldn&apos;t be here!</p>

<p><?php echo $securityMessage; ?></p>

<p>An email was sent to: <?php echo $to; ?>
<p>Notification Status: <?php echo ($notification ? "Email sent" : "Notification problem"); ?>
<div class="response">
	<?php echo $HTMLresponse; ?>
</div>

</body>
</html> 

