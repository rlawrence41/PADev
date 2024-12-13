<?php

/*
 *	testWidget - surrounds a widget with a Bootstrap page so that components will
 *				render correctly.
 */


include_once ("includes.php");
include_once ("testPage.class.php");
include_once ("pledgeSummary.class.php");
include_once ("common/config.php");
/* 
$eol = "\n<br/>";
echo "Include Path:  " . get_include_path() . $eol ;
echo "REST Root:  " . $GLOBALS['RESTroot'];
 */
$page = new testPage("testPage", "testPage", "Test Widget");

$widget = new pledgeSummaryWidget();
$page->addChild($widget);

$html = $page->render();
echo $html;
?>
