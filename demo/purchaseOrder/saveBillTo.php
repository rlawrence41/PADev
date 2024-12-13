<?php

/*
	saveBillTo.php updates the parent order record with the ship to contact.
	
	Updating the billing contact in a purchase order kicks off several things--requiring 
	access to other supporting resources.  	Most simple updates can be made in a single http 
	request from the client.  However, since this procedure makes several http requests, it 
	makes more sense to do this on the server.

 */

include_once ("../includes.php");
include_once ("poCommon.php");
	

function updateBillTo(){

	// Grab the parent key and ship to contact id from the querystring.
	if (!empty($_GET['order'])) $orderKey = $_GET['order'];
	if (!empty($_GET['billTo'])) $customerNo = $_GET['billTo'];

	// Make sure the parent transaction record is present in the result set.
	$orderObj = getOrder($orderKey);

	// Save the billing attributes to the parent order record.	
	$orderData = array();
	$orderData["id"] = $orderKey;
	$orderData["customerNo"] = $customerNo;

	// Obtain the billing contact.
	$billToObj = getContact($customerNo);

	// Capture the customer profile if available?
	$customerObj = getCustomer($customerNo);
	if (is_object($customerObj)){
		$orderData["salesRepNo"] = $customerObj->salesRepNo;
		$orderData["terms"] = $customerObj->terms;
		$orderData["termsDesc"] = $customerObj->termsDesc;
		$orderData["discount"] = $customerObj->discount;
		$orderData["lTaxable"] = !$customerObj->taxExempt;
	}

	// Post the results to the database.
#	$response = postToOrder($orderData);
	$response = updateOrder($orderData);

	return $response;


}

/******************************* Main Procedure ********************************************/

// Make sure the user is authorized.
if (authorized(1000)) {
	echo updateBillTo();
}

?>