<?php
include_once ("includes.php");
include_once ("payment.class.php");

/*
	paymentSearchList.php - This procedure instantiates a searchList component
					for the payment resource.  
					
					The searchList component will accept a search value and make a 
					GET request to the payment REST API for data filtered by that 
					search value.  It will respond with an HTML list with a subset of 
					the resulting data.
 */


$restURL = "/payment/rest/payment.php/payment";
$searchList = new searchList("id", $restURL);
#$searchList->setSearch("lastName,firstName,postalCode,email");
$searchList->setSearch("<<secondaryKey>>");
#$searchList->sortBy="lastName,firstName,postalCode,email";
$searchList->sortBy="<<sortList>>";
echo $searchList->render();