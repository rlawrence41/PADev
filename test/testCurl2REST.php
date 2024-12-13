<?php

// The following are all problematic.  The vm.demo.pubassist.crt certificate 
// is the right one to use, but I had a lot of trouble getting it to work.
#include_once "config.php";
#$sslCertPath = "/etc/apache2/ssl/vm.demo.pubassist.crt";
#$sslCertPath = "/etc/apache2/ssl/";
#$sslCertPath = "/etc/ssl/cacert.pem";

/*
 *	testCURL2REST is a simple script to demonstrate that this site can 
 *	successfully call another URL and get a response.
 */

$HTMLresponse = "<h2>Response should show here after posting.</h2>";

//  Set a default URL.
#$url = "https://" . $_SERVER['SERVER_NAME'];
$url = "https://dev.pubassist.com" ;
$url.="/contact/rest/contact.php/contact";

// If a URL and/or a search value were provided on the form, use them.
if (isset($_GET['url'])) {$url = $_GET['url'];}

//  Preserve the URL for the next post...
$lastUrl = $url;

//  Add the relaxed parameter for all searches...

$url .= "?compare=relaxed";

$searchVal = "";
if (isset($_GET['searchVal']) AND $_GET['searchVal'] > "") {
	
	$searchVal = $_GET['searchVal'];
	$url.="&searchVal={$searchVal}";

#	// Add the search value to the URL for the REST API call.
#	if (strpos($url, "?")){$url.="&searchVal={$searchVal}";}
#	else {$url.="?searchVal={$searchVal}";}
}
$submit = $_GET['submit'];
if ($submit=="Post to URL" and $url > ""){$HTMLresponse = postToURL($url);}


function postToURL($url) {
global $sslCertPath;

	if (isset($sslCertPath)){$certPath = $sslCertPath;}
	else ($certPath = "/etc/apache2/ssl/cacert.pem");

	$ch = curl_init( $url );
//  Do NOT specify a certificate path for the live server.  
//	And certainly do not use the vm.demo.pubassist.crt certificate.
//	curl_setopt( $ch, CURLOPT_CAINFO, "/etc/apache2/ssl/vm.demo.pubassist.crt");
	curl_setopt( $ch, CURLOPT_CAINFO, $certPath);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($ch);
	if ( ! ($response)) {
		$errno = curl_errno($ch);
		$errstr = curl_error($ch);
		$response = "testCURL2REST: cURL error: [$errno] $errstr.\n";
	}
	curl_close($ch);
	
	return $response;	

}
?>

<html>
<body>
<h1>Test Curl to REST API</h1>
<p>This procedure will submit an HTTP GET request to the URL specified in 
the form below.</p>

<p>CURL is viewed as the likely mechanism to launch REST requests from PHP.  
This will be especially true if/when the REST API is moved to a separate
server.  </p>

<p><b>Note that the success of this call depends on access to a valid (i.e. 
trusted) SSL certificate for the target URL.</b></p>

<p>
Keep in mind that you can search for text within the search fields by preceding 
your search value with a percent symbol.  For example, when searching titles, a search
value of "%House" will search for entries with the word "House" anywhere in the title or 
title Id. The search not NOT case sensitive.
</p>

<form method="get">

<table>
<tr>
	<td>URL:</td>
	<td><input type="text" size="100" name="url" value="<?php echo $lastUrl; ?>" /></td>
</tr>
<tr>
	<td>Search For:</td>
	<td><input type="text" size="100" name="searchVal" value="<?php echo $searchVal; ?>" /></td>
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