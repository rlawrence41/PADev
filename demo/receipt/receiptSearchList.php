<?php
include_once ("includes.php");
include_once ("receipt.class.php");

/*
	receiptSearchList.php - This procedure instantiates a searchList component
					for the receipt resource.  
					
					The searchList component will accept a search value and make a 
					GET request to the receipt REST API for data filtered by that 
					search value.  It will respond with an HTML list with a subset of 
					the resulting data.
 */


$searchList = new searchList("id", "receipt");
#$searchList->setSearch("lastName,firstName,postalCode,email");
$searchList->setSearch("<<secondaryKey>>");
#$searchList->sortBy="lastName,firstName,postalCode,email";
$searchList->sortBy="<<sortList>>";
echo $searchList->render();