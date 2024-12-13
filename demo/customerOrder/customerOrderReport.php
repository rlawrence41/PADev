<?php
include_once ("includes.php");
include_once ("customerOrder.class.php");
include_once ("customerOrderRpt.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {

	$page = new customerOrderRptPage();
	echo $page->render();
}
?>
