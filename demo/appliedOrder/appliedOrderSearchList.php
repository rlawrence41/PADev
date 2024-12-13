<?php
include_once ("includes.php");
include_once ("appliedOrder.class.php");

/*
	appliedOrderSearchList.php - This procedure instantiates a searchList component
					for the appliedOrder resource.  
					
					The searchList component will accept a search value and make a 
					GET request to the appliedOrder REST API for data filtered by that 
					search value.  It will respond with an HTML list with a subset of 
					the resulting data.
 */


$searchList = new searchList("id", "appliedOrder");
#$searchList->setSearch("lastName,firstName,postalCode,email");
$searchList->setSearch("<<secondaryKey>>");
#$searchList->sortBy="lastName,firstName,postalCode,email";
$searchList->sortBy="<<sortList>>";
echo $searchList->render();