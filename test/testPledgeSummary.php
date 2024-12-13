<?php
include_once ("includes.php");
include_once ("testPage.class.php");
include_once ("pledgeSummary.class.php");

// Login.php looks for the referring page in the Session variable.
// Normally, the referring page is set by the common function, authorize().
// This application does NOT require the user to log in until they decide to 
// enter a pledge.  Yet, we want to present the pledge summary table.
// So, set the referring page prior to rendering the pledge widget.
$_SESSION['HTTP_REFERER'] = $_SERVER['REQUEST_URI'];

$page = new testPage();
$widget = new pledgeSummaryWidget();
$page->addChild($widget);
echo $page->render();

?>
