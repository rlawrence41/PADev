<?php

//  Set up a series of page controls. 
$eol = "<br/>\n";
include ("includes.php");

$control1 = new pageControl('Previous', "disabled");
$html = $control1->render();
$control2 = new pageControl('First', "current");
$html .= $control2->render();
$control3 = new pageControl('2');
$html .= $control3->render();
$control4 = new pageControl(strval(lastPage));
$html .= $control4->render();
$control5 = new pageControl('Next');
$html .= $control4->render();
echo $html;