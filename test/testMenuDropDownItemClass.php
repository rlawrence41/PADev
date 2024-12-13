<?php

// 
$eol = "\n";
include ("includes.php");
//include ("menuDropDownItem.class.php");

$menuItem1 = new menuDropDownItem('Set a filter', "newFilter()");
$html = $menuItem1->render() . $eol;
$menuItem1 = new menuDropDownItem('Clear filter', "clearFilter()");
$html .= $menuItem1->render() . $eol;

echo $html;