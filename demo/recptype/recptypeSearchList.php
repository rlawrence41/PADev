<?php
include_once ("includes.php");
include_once ("recptype.class.php");

/*
	recptypeSearchList.php - This procedure instantiates a searchList component
					for the recptype resource.  
					
					The searchList component will accept a search value and make a 
					GET request to the recptype REST API for data filtered by that 
					search value.  It will respond with an HTML list with a subset of 
					the resulting data.
 */


$searchList = new searchList("recptType", "recptype");
#$searchList->setSearch("lastName,firstName,postalCode,email");
$searchList->setSearch("recptType");
#$searchList->sortBy="lastName,firstName,postalCode,email";
$searchList->sortBy="recptType";
echo $searchList->render();