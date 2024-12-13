<?php

// 
$eol = "<br/>\n";
include ("includes.php");
//include ("field.class.php");

$field1 = new field('company', 'text', "Company", "Company or organization name");
$html = $field1->render();
$field2 = new field('email','email', "Email", "e.g. username@domain.com");
$html .= $field2->render();
$field3 = new field('biography', 'textarea', "Biography", "Enter a promotional biography for the contact.");
$html .= $field3->render();
$field4 = new field('author', 'checkbox', "Author?", "", true);
$html .= $field4->render();
echo $html;