<?php
include_once ("includes.php");
include_once ("title.class.php");

/*
	titleSearchList.php - This procedure instantiates a searchList component
					for the title resource.  
					
					The searchList component will accept a search value and make a 
					GET request to the title REST API for data filtered by that 
					search value.  It will respond with an HTML list with a subset of 
					the resulting data.
 */

#debug_msg("REST root: " . $GLOBALS['RESTroot'], true, "titleSearchList.php") ;

$searchList = new searchList("id", "title");
#$searchList->setSearch("lastName,firstName,postalCode,email");
$searchList->setSearch("titleId,title");
#$searchList->sortBy="lastName,firstName,postalCode,email";
$searchList->sortBy="titleId,title";
echo $searchList->render();