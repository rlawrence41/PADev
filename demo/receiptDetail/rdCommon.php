<?php

/*
	rdCommon.php is a set of common routines for the receipt detail transaction.

 */


// Get the submitted contact from REST. 
function getContact($id) {

	// Capture the contact.
	// Sample REST call:
	//https://dev.pubassist.com/contact/rest/contact.php/contact/{$shipToNo}
	
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'];
	$url .= "/contact/rest/contact.php/contact/{$id}" ;
	$jsonStr = postToURL($url);
	$jsonObj = json_decode($jsonStr);
	
	// Skip the count.  
	// Even though a single record is returned, it is still the first cell of an array.
	if (is_array($jsonObj)) return $jsonObj[1][0];
	else {
		$message = json_last_error_msg();
		debug_msg("getContact:  " . $message, true);
		return null;
	}

}


// Get the parent receipt record from REST. 
function getReceipt($receiptNo) {
	
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'];
	$url .= "/receiptDetail/rest/receiptDetail.php/receiptDetail/{$receiptNo}" ;
	$jsonStr = postToURL($url);
	$jsonObj = json_decode($jsonStr);
	
	// Skip the count.  
	// Even though a single record is returned, it is still the first cell of an array.
	if (is_array($jsonObj)) return $jsonObj[1][0];
	else {
		$message = json_last_error_msg();
		debug_msg("getReceipt:  " . $message, true);
		return null;
	}
}


function postToReceipt($receiptData){
	
	// Add the trace fields...
	addTraceFields($receiptData);

	// Encode as JSON...
	$jsonData = json_encode($receiptData);

	// Post the update to REST.
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'] . "/receipt/rest/receipt.php" ;
	$response = postJsonToURL($url, $jsonData);

	// Drop the table properties from the $_SESSION...
	if (isset($_SESSION['tableProperties'])) unset($_SESSION['tableProperties']);

	return $response;
	
}



?>