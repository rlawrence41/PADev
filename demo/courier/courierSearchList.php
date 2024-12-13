<?php
include_once ("includes.php");
include_once ("courier.class.php");

/*
	courierSearchList.php - This procedure instantiates a searchList component
					for the courier resource.  
					
					The searchList component will accept a search value and make a 
					GET request to the courier REST API for data filtered by that 
					search value.  It will respond with an HTML list with a subset of 
					the resulting data.
 */

#debug_msg("REST root: " . $GLOBALS['RESTroot'], true, "courierSearchList.php") ;

// Note: returning the courier name, but the id is the key.
$searchList = new searchList("courier", "courier");

#$searchList->setSearch("lastName,firstName,postalCode,email");
$searchList->setSearch("courier");
#$searchList->sortBy="lastName,firstName,postalCode,email";
$searchList->sortBy="lUSPS,courier";
echo $searchList->render();