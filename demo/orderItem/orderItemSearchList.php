<?php
include_once ("includes.php");
include_once ("orderItem.class.php");

/*
	orderItemSearchList.php - This procedure instantiates a searchList component
					for the orderItem resource.  
					
					The searchList component will accept a search value and make a 
					GET request to the orderItem REST API for data filtered by that 
					search value.  It will respond with an HTML list with a subset of 
					the resulting data.
 */


$searchList = new searchList("id", "orderItem");
#$searchList->setSearch("lastName,firstName,postalCode,email");
$searchList->setSearch("<<secondaryKey>>");
#$searchList->sortBy="lastName,firstName,postalCode,email";
$searchList->sortBy="<<sortList>>";
echo $searchList->render();