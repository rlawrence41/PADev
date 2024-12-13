<?php
include_once ("includes.php");
include_once ("contact.class.php");

/*
	contactSearchList.php - This procedure instantiates a searchList component
					for the contact resource.  
					
					The searchList component will accept a search value and make a 
					GET request to the contact REST API for data filtered by that 
					search value.  It will respond with an HTML list with a subset of 
					the resulting data.
 */


$searchList = new searchList("id", "contact");
#$searchList->setSearch("<<secondaryKey>>");
$searchList->setSearch("contactId,company,lastName,firstName,zipCode,email");
#$searchList->sortBy="<<sortList>>";
$searchList->sortBy="company,lastName,firstName,zipCode,email";
echo $searchList->render();