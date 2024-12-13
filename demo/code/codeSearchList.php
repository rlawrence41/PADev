<?php
include_once ("includes.php");
include_once ("code.class.php");

/*
	codeSearchList.php - This procedure instantiates a searchList component
					for the code resource.  
					
					The searchList component will accept a search value and make a 
					GET request to the code REST API for data filtered by that 
					search value.  It will respond with an HTML list with a subset of 
					the resulting data.
 */


$searchList = new searchList("id", "code");
#$searchList->setSearch("lastName,firstName,postalCode,email");
$searchList->setSearch("<<secondaryKey>>");
#$searchList->sortBy="lastName,firstName,postalCode,email";
$searchList->sortBy="<<sortList>>";
echo $searchList->render();