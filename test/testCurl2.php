<?php

/*
 *	testCURL is a simple script to demonstrate that this site can successfully call another URL
 *	and get a response.
 *
 *	testCURL2 is a variation intended to show the SSL certificate verification when run
 *	from Apache.
 */

include_once("includes.php");

$HTMLresponse = "<h2>Response should show here after posting.</h2>";
$serverName = $_SERVER['SERVER_NAME'] ;
$restURL = "https://" . $serverName . "/contact/rest/contact.php/contact/1" ;

// Post a request to the submitted URL.
if (isset($_POST['url'])) {
	$HTMLresponse = postToURL2($_POST['url']);
}


/*
 *	postToURL() -- uses CURL to forward an HTTP request from the server.
 */
function postToURL2($url) {
global $sslCertPath;

#	echo "Posting to URL: " . $url;
	$ch = curl_init( $url );

	// Assign CAINFO to the specific SSL Cert ONLY IF it is self-authorized.
#	curl_setopt( $ch, CURLOPT_CAINFO, "/etc/apache2/ssl/vm.demo.pubassist.crt");
	curl_setopt( $ch, CURLOPT_CAINFO, $sslCertPath);
#	curl_setopt( $ch, CURLOPT_CAINFO, "/etc/apache2/ssl/cacert.pem" );
#	curl_setopt( $ch, CURLOPT_CAINFO, "/etc/letsencrypt/live/app.ccgopvt.org/fullchain.pem" );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_CERTINFO, 1);
	curl_setopt( $ch, CURLOPT_VERBOSE, 1);
	
	

	$response = curl_exec($ch);
//	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ( ! ($response)) {
		$errno = curl_errno($ch);
		$errstr = curl_error($ch);
		$response = "postToURL - CURL error: [$errno] $errstr.";
	}
	curl_close($ch);
	
	return $response;	
	
}


?>

<html>
<body>
<h1>Test Curl</h1>
<p>This procedure will post an HTTP request to the URL specified in the form below.</p>

<form method="post">

<table>
<tr>
	<td>URL:</td>
	<td><input type="text" size="100" name="url" value="<?php echo $restURL ?>" /></td>
</tr>

<tr><td colspan=2><input type="submit" name="submit" value="Post to URL" /></td></tr>
</table>

</form>

<div class="response">
	<?php echo $HTMLresponse; ?>
</div>
<p><a href="/">Back to the Test menu</a></p>

</body>
</html>