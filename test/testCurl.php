<?php

/*
 *	testCURL is a simple script to demonstrate that this site can successfully call another URL
 *	and get a response.
 */

$HTMLresponse = "<h2>Response should show here after posting.</h2>";
 
if (isset($_POST['url'])) {
	$HTMLresponse = postToURL2($_POST['url']);
}


function packagePostData() {
	
	// Assign posted variables to an array.
/* 	item_name = request.form("item_name")
	item_number = request.form("item_number")
	payment_status = request.form("payment_status")
	payment_amount = request.form("mc_gross")
	payment_currency = request.form("mc_currency")
	txn_id = request.form("txn_id")
	receiver_email = request.form("receiver_email")
	payer_email = request.form("payer_email")
	invoice = request.form("invoice")
	first_name = request.form("first_name")
	last_name = request.form("last_name")
	payer_id = request.form("payer_id")
 */
 
	$postData = array();
	
	// Add actual form data...
	$postData['invoice'] = $_POST['invoice'];
	$postData['amount'] = $_POST['amount'];	

	// The following list was taken from PayPaltest.html.
	
	$postData['test_ipn'] = '1';
	$postData['payment_type'] = 'instant';
	$postData['payment_date'] = '06%3A14%3A15+Jan.+01%2C+2009+PST';
	$postData['payment_status'] = 'Completed';
	$postData['payer_status'] = 'verified';
	$postData['first_name'] = 'John';
	$postData['last_name'] = 'Smith';
	$postData['payer_email'] = 'buyer%40paypalsandbox.com';
	$postData['payer_id'] = 'TESTBUYERID01';
	$postData['business'] = 'seller%40paypalsandbox.com';
	$postData['receiver_email'] = 'seller%40paypalsandbox.com';
	$postData['receiver_id'] = 'TESTSELLERID1';
	$postData['residence_country'] = 'US';
	$postData['item_name1'] = 'something';
	$postData['item_number1='] = 'AK-1234';
	$postData['quantity1'] = '1';
	$postData['tax'] = '2.02';
	$postData['mc_currency'] = 'USD';
	$postData['mc_fee'] = '0.44';
	$postData['mc_gross'] = '15.34';
	$postData['mc_gross1'] = '12.34';
	$postData['mc_handling'] = '2.06';
	$postData['mc_handling1'] = '1.67';
	$postData['mc_shipping'] = '3.02';
	$postData['mc_shipping1'] = '1.02';
	$postData['txn_type'] = 'cart';
	$postData['txn_id'] = '15111414';
	$postData['notify_version'] = '2.4';
	$postData['custom'] = 'xyz123';
//	$postData['invoice'] = 'abc1234';
	$postData['charset'] = 'windows-1252';
	$postData['verify_sign'] = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AYzMp3WfgEmrKh-0gJvE2sVce3Jq';
	$postData['cmd'] = '_notify-validate'; 	
	
	// Package the post data as a string.
	foreach($postData as $key => $value) {
		if (strlen($returnData) > 0) {$returnData .= '&';} 
		$returnData .= "$key=$value";
	}
	
	return $returnData;
	
}



function postToURL2($url) {

/* 	$invoice = $_POST['invoice'];
	$amount = $_POST['amount'];	
	$myvars = 'invoice=' . $invoice . '&amount=' . $amount;
 */
 
	$myvars = packagePostData();
	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($ch);
	if ( ! ($response)) {
		$errno = curl_errno($ch);
		$errstr = curl_error($ch);
		error_log("testCURL: cURL error: [$errno] $errstr.\n", 0);
	}
	curl_close($ch);
	
	return $response;	
	
}

 
function postToURL($url) {
	$postData = array();
	$postData['invoice'] = $_POST['invoice'];
	$postData['amount'] = $_POST['amount'];	
	$req = json_encode($$postData);
	$use_local_certs = true;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSLVERSION, 6);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	// This is often required if the server is missing a global cert bundle, or is using an outdated one.
	if ($use_local_certs) {
		// Using self-signed certificate for testing.
		curl_setopt($ch, CURLOPT_CAINFO, "/etc/apache2/ssl/apache2.crt");
	}
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'User-Agent: PubassistPaymentListener',
		'Content-Type: application/json',
		'Connection: Close'
	));

//	'Content-Type: application/x-www-form-urlencoded',
	
	$response = curl_exec($ch);
	if ( ! ($response)) {
		$errno = curl_errno($ch);
		$errstr = curl_error($ch);
		curl_close($ch);
		error_log("testCURL: cURL error: [$errno] $errstr.\n", 0);
	}

	// curl_getinfo() seems not to be returning anything.  (Ron Lawrence)
	error_log("testCURL: Checking the results of the post.", 0);	// Ron Lawrence
	$info = curl_getinfo($ch);
	if ($info) {
		$http_code = $info['http_code'];
		error_log("testCURL: $url responded with http code: ${http_code}\n", 0);
		if ($http_code != 200) {
	#	            throw new Exception("PayPal responded with http code $http_code");
			error_log("testCURL: $url responded with http code: ${http_code}\n", 0);
			foreach ($info as $key => $value) {
				error_log("{$key} => {$value}\n", 0);
			}
		}
	}
	else {error_log("testCURL:  No response from curl_getinfo().\n", 0);}

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
	<td><input type="text" size="100" name="url" value="https://pubassist.com/PaymentListener.asp" /></td>
</tr>
<tr>
	<td>Invoice:</td>
	<td><input type="text" size="100" name="invoice" value="12345-01" /></td>
</tr>
<tr>
	<td>Amount:</td>
	<td><input type="text" size="100" name="amount" value="99.99" /></td>
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