<?php

// 
$eol = "<br/>\n";
include ("includes.php");
include ("foreignKey.class.php");

// Define foreign keys for the Title Liability application.
// Parameters: fieldName, Label, foreign table, foreign Key field, placeholder.

$field1 = new foreignKey('payeeNo', "Payee", 'contact', "contact_no", "last name, first name, company");
$html = $field1->render();
$field2 = new foreignKey('titleNo', "Title", 'title', "title_no", "Enter a title to associate.");
$html .= $field2->render();
echo $html;