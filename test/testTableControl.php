<?php

// TestTableControl.php - This procedure is intended to test the tableControl 
//					class.

include ("includes.php");
include_once("contactTable.class.php");

// The following variables are specific to the contact resource.
$resource = "contact";
$keyFieldName = "contact_no";
$restURI = "https://" . $_SERVER['SERVER_NAME'] .
			"/contact/contactREST.php/contact";

// Create the table control.
$tc = new tableControl("tableControl", "tc1");

// Add a context component.
$context = new context($resource, $keyFieldName, $restURI);
$tc->addContext($context);

// Add a resource-specific table.
	$table = new contactTable($keyFieldName);
	$table->addContext($context);
	$table->getData();
	$table->addContent();
$tc->addChild($table);

// Respond with a rendering of the table control.
echo $tc->render();