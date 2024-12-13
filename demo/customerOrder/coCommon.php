<?php

/*
	coCommon.php is a set of common routines for the customer order transaction.
	
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


// Get the submitted courier from REST. 
function getCourier($courier) {

	// Capture the courier.
	// Sample REST call:
	//https://dev.pubassist.com/courier/rest/courier.php?column[courier]={$courier}
	
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'];
	$url .= "/courier/rest/courier.php/courier/{$courier}" ;
	$jsonStr = postToURL($url);
	$jsonObj = json_decode($jsonStr);
	
	// Skip the count.  
	// Even though a single record is returned, it is still the first cell of an array.
	if (is_array($jsonObj)) return $jsonObj[1][0];
	else {
		$message = json_last_error_msg();
		debug_msg("getCourier:  " . $message, true);
		return null;
	}

}


// Get the submitted customer profile from REST. 
function getCustomer($customerNo) {
	
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'];
	$url .= "/customer/rest/customer.php/customer/{$customerNo}" ;
	$jsonStr = postToURL($url);
	$jsonObj = json_decode($jsonStr);
	
	// Skip the count.  
	// Even though a single record is returned, it is still the first cell of an array.
	if (is_array($jsonObj)) return $jsonObj[1][0];
	else {
		$message = json_last_error_msg();
		debug_msg("getCustomer:  " . $message, true);
		return null;
	}


}


// Get the sums from the ordered item records from REST. 
function getItemTotals($orderKey) {
	
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'];
	$url .= "/itemTotal/rest/itemTotal.php?column[orderKey]={$orderKey}" ;
	$jsonStr = postToURL($url);
	$jsonObj = json_decode($jsonStr);
	
	// Skip the count.  
	// There could be two records in the collection for taxable and non-taxable.
	if (is_array($jsonObj)) return $jsonObj[1];
	else {
		$message = json_last_error_msg();
		debug_msg("getItems:  " . $message, true);
		return null;
	}
}


// Get the local tax rate for the shipping address from REST. 
function getLocalTaxRate($stateAbbr, $munAbbr) {

#	debug_msg("get: Here we are!", true);
	
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'];
	$url .= "/localTaxRate/rest/localTaxRate.php/localTaxRate?column[stateAbbr]={$stateAbbr}&column[munAbbr]={$munAbbr}&compare=strict" ;
	$jsonStr = postToURL($url);
	$jsonObj = json_decode($jsonStr);

#	debug_msg("get Json: " . print_r($jsonObj), true);
	
	// Skip the count.  
	// Even though a single record is returned, it is still the first cell of an array.
	if (is_array($jsonObj)) return $jsonObj[1][0];
	else {
		$message = json_last_error_msg();
		debug_msg("getLocalTaxRate:  " . $message, true);
		return null;
	}

}


// Get the parent order record from REST. 
function getOrder($orderKey) {
	
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'];
	$url .= "/orders/rest/orders.php/orders/{$orderKey}" ;
	$jsonStr = postToURL($url);
	$jsonObj = json_decode($jsonStr);
	
	// Skip the count.  
	// Even though a single record is returned, it is still the first cell of an array.
	if (is_array($jsonObj)) return $jsonObj[1][0];
	else {
		$message = json_last_error_msg();
		debug_msg("getOrder:  " . $message, true);
		return null;
	}

}


// Get the state tax rate for the shipping address from REST. 
function getStateTaxRate($stateAbbr) {
	
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'];
	$url .= "/stateTaxRate/rest/stateTaxRate.php/stateTaxRate?column[stateAbbr]={$stateAbbr}&compare=strict" ;
	$jsonStr = postToURL($url);
	$jsonObj = json_decode($jsonStr);
	
	// Skip the count.  
	// Even though a single record is returned, it is still the first cell of an array.
	if (is_array($jsonObj)) return $jsonObj[1][0];
	else {
		$message = json_last_error_msg();
		debug_msg("getStateTaxRate:  " . $message, true);
		return null;
	}

}


function postToOrder($orderData){
	
	// Add the trace fields...
	addTraceFields($orderData);

	// Encode as JSON...
	$jsonData = json_encode($orderData);

	// Post the update to REST.
	$url = $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'] . "/orders/rest/orders.php" ;
	$response = postJsonToURL($url, $jsonData);

	// Drop the table properties...
	if (isset($_SESSION['tableProperties'])) unset($_SESSION['tableProperties']);

	return $response;
	
}


function updateOrder($orderData=array()){

	// Grab the parent key and ship to contact id from the querystring.
	if (!empty($_GET['order'])) $orderKey = $_GET['order'];

	// Make sure the parent transaction record is present.
	$orderObj = getOrder($orderKey);
	$orderData["id"] = $orderKey;

	// Several of the orders fields are NOT NULL.
	// Initializing these variables ensures that their values will not be null
	// when updating...
	$shipWeight 	= 0.00;
	$subtotal 		= 0.00;
	$noTaxSubtotal 	= 0.00;
	$stateTax    	= 0.00;
	$localTax    	= 0.00; 
	$total       	= 0.00;

	// Capture the subtotals from ordered items.	
	$itemTotals = getItemTotals($orderKey);

	// Struggling with names.  $subtotals is an array of itemTotal json records.
	//                         ----------
	
	foreach ($itemTotals as $index=>$subtotals){
		$shipWeight += $subtotals->shipWeight;
		if ($subtotals->lTaxable==true) $subtotal = $subtotals->subtotal;
		else $noTaxSubtotal = $subtotals->subtotal;
	}
	$orderData["shipWeight"] = $shipWeight;
	
	// Calculate the taxes.
	$taxSubtotal = $subtotal;
	if ($orderObj->lTaxable){
		if ($orderObj->lTaxShip==true){
			$taxSubtotal += $orderObj->shipCharge;
		}
		$stateTax = ceil($taxSubtotal * $orderObj->stateTaxRate)/100;
		$localTax = ceil($taxSubtotal * $orderObj->localTaxRate)/100;
	}

	$total = $taxSubtotal + $noTaxSubtotal +
			$orderObj->shipCharge +
			$orderObj->adjustment1 +
			$orderObj->adjustment2;
	
	$orderData["shipWeight"] = $shipWeight;
	$orderData["subtotal"] = $subtotal;
	$orderData["noTaxSubtotal"] = $noTaxSubtotal;
	$orderData["stateTax"] = $stateTax;
	$orderData["localTax"] = $localTax;	
	$orderData["total"] = $total;

	// Post the results to the database.
	$response = postToOrder($orderData);
	return $response;

}

?>