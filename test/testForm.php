<?php

// This procedure is used to test the form class.  View the page source
//	to verify whether the appropriate HTML was generated.

$eol = "<br/>\n";
include ("includes.php");

$form = new form("Contact");	// Parameter describes what we are editing.

$fieldGroup1 = new fieldGroup("Name");
$fieldGroup1->addField('id','hidden', "record identifier", "");
$fieldGroup1->addField('contact_no', 'text', "Contact Number", "");
$fieldGroup1->addField('contact_id', 'text', "Contact Id", "Short name for the account");
$fieldGroup1->addField('company', 'text', "Company", "Company or organization name");
$fieldGroup1->addField('nameprefix', 'text', "Prefix", "e.g.: Mr., Mrs., Ms., Dr., etc.");
$fieldGroup1->addField('first_name', 'text', "First Name", "");
$fieldGroup1->addField('mid_name', 'text', "Middle Name", "middle name or initial");
$fieldGroup1->addField('last_name', 'text', "Last Name", "");
$form->addGroup($fieldGroup1);

$fieldGroup2 = new fieldGroup("Address");
$fieldGroup2->addField('po_addr', 'textarea', "Street", "Postal Service Street Address ");
$fieldGroup2->addField('cour_addr', 'textarea', "Courier Address", "Complete street address for courier services");
$fieldGroup2->addField('city', 'text', "City", "City or town");
$fieldGroup2->addField('state_abbr', 'text', "State", "State Abbreviation");
$fieldGroup2->addField('country', 'text', "Country", "USA");
$fieldGroup2->addField('zip_code', 'text', "Zip Code", "Postal Code");
$fieldGroup2->addField('countyabbr', 'text', "County", "County Abbreviation");
$form->addGroup($fieldGroup2);

$fieldGroup3 = new fieldGroup("Contact");
$fieldGroup3->addField('phone', 'text', "Phone", "Enter the primary phone number.");
$fieldGroup3->addField('phone2', 'text', "Phone2", "Enter an alternate phone, FAX or cell number.");
$fieldGroup3->addField('email', 'text', "Email", "e.g. username@domain.com");
$fieldGroup3->addField('web_url', 'text', "Web Site", "Enter the URL of this contact\'s web site.");
$fieldGroup3->addField('webservice', 'text', "Web Service", "Enter the URL of a web service offered by this contact.");
$form->addGroup($fieldGroup3);

$html = $form->render();
echo $html;

