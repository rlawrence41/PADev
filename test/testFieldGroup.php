<?php

// This procedure is used to test the fieldGroup class.  View the page source
//	to verify whether the appropriate HTML was generated.

$eol = "<br/>\n";
include ("includes.php");

// Identifiers
$fieldGroup = new fieldGroup("Identifiers", true);
$field = new inputHidden('contact_no', 'contactNo', "Contact Number", "");
$fieldGroup->addChild($field);
$field = new inputText('contact_id', 'contactId', "Contact Id", "Short, memorable name for the account");
$fieldGroup->addChild($field);
$field = new inputText('company', 'company', "Company", "Company or organization name");
$fieldGroup->addChild($field);
$field = new inputText('nameprefix', 'namePrefix', "Prefix", "e.g.: Mr., Mrs., Ms., Dr., etc.");
$fieldGroup->addChild($field);
$field = new inputText('first_name', 'firstName', "First Name", "first name");
$fieldGroup->addChild($field);
$field = new inputText('mid_name', 'middleName', "Middle Name", "middle name or initial");
$fieldGroup->addChild($field);
$field = new inputText('last_name', 'lastName', "Last Name", "last name");
$fieldGroup->addChild($field);
#$this->addChild($fieldGroup);


echo "Group Name: " . $fieldGroup->name . $eol; 
echo "Group Id: " . $fieldGroup->htmlId . $eol;

$html = $fieldGroup->render();
echo $html;

