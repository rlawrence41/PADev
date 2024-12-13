<?php

/*
	saveShipTo.php updates the parent order record with the ship to contact.
	
	Updating the shipping contact in a customer order kicks off several things--requiring 
	access to other supporting resources.  	Most simple updates can be made in a single http 
	request from the client.  However, since this procedure makes several http requests, it 
	makes more sense to do this on the server.

 */

include_once ("../includes.php");
include_once ("coCommon.php");


function updateShipTo(){

	// Grab the parent key and ship to contact id from the querystring.
	if (!empty($_GET['order'])) $orderKey = $_GET['order'];
	if (!empty($_GET['shipTo'])) $shipToNo = $_GET['shipTo'];


	// Make sure the parent transaction record is present in the result set.
	$orderObj = getOrder($orderKey);

	// Obtain the shipping contact.
	$shipToObj = getContact($shipToNo);
	
	// Assemble the U.S.P.S. address.
	$shipToAddr = getPOAddress($shipToObj);	// in common.php

	// Capture the shipping attributes for the order record.	
	$orderData = array();
	$orderData["id"] = $orderKey;
	$orderData["shipToNo"] = $shipToNo;

	// Obtain a state tax rate for the shipping contact.
	$sTaxObj = getStateTaxRate($shipToObj->stateAbbr);
	if (isset($sTaxObj->taxRate)) {
		$orderData["stateTaxRate"]=$sTaxObj->taxRate;	
		$orderData["lTaxShip"]=$sTaxObj->lTaxShip;		// Tax shipping?
	}
	
	// Is there a local tax for the shipping contact.
	$orderData["localTaxRate"]= 0.00;
	$lTaxObj = getLocalTaxRate($shipToObj->stateAbbr, $shipToObj->munAbbr);
	if (isset($lTaxObj->taxRate))
		$orderData["localTaxRate"]=$lTaxObj->taxRate;

	// Retrieve the courier service details.
	$courierObj = getCourier($orderObj->courier);
	if (is_object($courierObj)){
		$orderData["courier"] = $courierObj->courier;

		// If the courier service is a U.S.P.S. service, return the postal address.
		if (!$courierObj->lUSPS){
			if (strlen(trim($shipToObj->courAddr)) > 0)	
				$shipToAddr = $shipToObj->courAddr;
		}
	
		// Since we have the courier record, it makes sense to calculate the shipping costs
		// here.

		$shipCharge = 0.00;
		if (empty($orderObj->shipCharge)) {
			$fixedAmt = $courierObj->fixedAmt;
			$shipWeight = ceil($orderObj->shipWeight);
			$threshold = $courierObj->threshold;
			$variableAmt = $courierObj->variableAmt;
			$shipCharge = $fixedAmt + (($shipWeight - $threshold)* $variableAmt);
		}
		if ($shipCharge > 0) $orderData["shipCharge"] = $shipCharge;
	}

	$orderData["shipToAddr"] = $shipToAddr;

	// If the shipping contact specifies a billing contact, or if the billing
	// contact is empty, then update the billing contact for the order.

	if ($shipToObj->billTo > 0) $orderData["customerNo"] = $shipToObj->billTo;
	else if (empty($orderObj->customerNo)) $orderData["customerNo"] = $shipToNo;


	// Post the results to the database.
#	$response = postToOrder($orderData);
	$response = updateOrder($orderData);
	return $response;
}

/******************************* Main Procedure ********************************************/

#debug_msg("Debugging saveShipTo: start.", true);


// Make sure the user is authorized.
if (authorized(1000)) {
	echo updateShipTo();
}

?>