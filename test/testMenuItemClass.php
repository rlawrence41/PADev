<?php

// This procedure is used to test the menumenuItem class.  View the page source
//	to verify whether the appropriate HTML was generated.

$eol = "<br/>\n";
include ("includes.php");
//include ("menuItem.class.php");

$menuItem1 = new menuItem("Filter", "filterStr", "newFilter()");
$html = $menuItem1->render();
echo $html;

