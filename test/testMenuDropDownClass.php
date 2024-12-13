<?php

// This procedure is used to test the menuDropDown class.  View the page source
//	to verify whether the appropriate HTML was generated.

$eol = "<br/>\n";
include ("includes.php");
//include ("menuDropDownItem.class.php");
//include ("menuDropDown.class.php");

$dropDown1 = new menuDropDown("Filter");
$dropDown1->addDropDownItem("Set a filter");
$dropDown1->addDropDownItem("Remove filter");
$html = $dropDown1->render();
echo $html;

