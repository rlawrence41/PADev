<?php

// This procedure is used to test the form class.  View the page source
//	to verify whether the appropriate HTML was generated.

include_once ("includes.php");
include_once ("loginForm.class.php");

// The following variables are specific to the contact resource.
$title = "Test Form Class";
$resource = "contact";
$keyFieldName = "contact_no";
$restURI = "https://" . $_SERVER['SERVER_NAME'] .
			"/contact/contactREST.php/contact";

// Create the page.
$page = new page($title, 
				$resource, 
				$keyFieldName, 
				$restURI);

$menu = new standardMenu();
$page->addChild($menu);

$form = new form("contact", "adminForm");
addFieldsToForm($form);
$page->addChild($form);

$html = $page->render();
echo $html;


function addFieldsToForm($form){

	// Identifiers
	$fieldGroup = $form->addFieldGroup("Identifiers", true);
	$fieldGroup->addField("Hidden", "contact_no", "contactNo", "Contact Number", "");
	$fieldGroup->addField("Text", "contact_id", "contactId", "Contact Id", "Short, memorable name for the account");
	$fieldGroup->addField("Text", "company", "company", "Company", "Company or organization name");
	$fieldGroup->addField("Text", "nameprefix", "namePrefix", "Prefix", "e.g.: Mr., Mrs., Ms., Dr., etc.");
	$fieldGroup->addField("Text", "first_name", "firstName", "First Name", "first name");
	$fieldGroup->addField("Text", "mid_name", "middleName", "Middle Name", "middle name or initial");
	$fieldGroup->addField("Text", "last_name", "lastName", "Last Name", "last name");

	// Address fields...
	$form->addFieldGroup("Address");
	$fieldGroup->addField("Textarea", "po_addr", "postalAddress", "Street", "Postal Service Street Address ");
	$fieldGroup->addField("Text", "city", "city", "City", "City or town");
	$fieldGroup->addField("Text", "state_abbr", "stateAbbr", "State", "State Abbreviation");
	$fieldGroup->addField("Text", "country", "country", "Country", "USA");
	$fieldGroup->addField("Text", "zip_code", "zipCode", "Zip Code", "Postal Code");
	$fieldGroup->addField("Textarea", "cour_addr", "courierAddress", "Courier Address", "Complete physical address for courier service");
	$fieldGroup->addField("Text", "countyabbr", "countyAbbr", "County", "County Abbreviation");

	// Contact fields...
	$form->addFieldGroup("Contact");
	$fieldGroup->addField("Text", "phone", "phone", "Phone", "Enter the primary phone number.");
	$fieldGroup->addField("Text", "phone2", "phone2", "Phone2", "Enter an alternate phone, FAX or cell number.");
	$fieldGroup->addField("Email", "email", "email", "Email", "e.g. username@domain.com");
	$fieldGroup->addField("Text", "web_url", "webURL", "Web Site", "Enter the URL of this contact's web site.");
	$fieldGroup->addField("Text", "webservice", "webService", "Web Service", "Enter the URL of a web service offered by this contact.");
	
}


