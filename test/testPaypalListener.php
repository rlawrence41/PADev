<?php 

// This is NOT the real PayPal IPN listener.  This procedure is used to test elements of that
// listener.
// Log a message to syslog.
error_log("Testing the PayPal IPN Listener", 0);

/* clearstatcache();
$EOL = "<br/>\n";
print $_SERVER['SCRIPT_FILENAME'] . $EOL;
$filename = $_SERVER['DOCUMENT_ROOT']."/paypal/paypalIPN.php";
if (is_file($filename)) {
    echo "The file $filename exists $EOL" ;
} else {
    echo "The file $filename does not exist $EOL";
} */

require($filename);
#use PaypalIPN;

// Use the sandbox endpoint during testing.
$ipn = new PaypalIPN;
print "Default URI: " . $ipn->getPaypalUri() . $EOL;
$ipn->useSandbox();
print "Assigned URI: " . $ipn->getPaypalUri() . $EOL;


//Let's dump the content of the IPN.
foreach($_POST as $key => $value) {
#	error_log("$key = $value</br>\n", 0);
	print "$key = $value $EOL";
}

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
header("HTTP/1.1 200 OK");
?>