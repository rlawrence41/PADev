<?php
include_once ("includes.php");
include_once ("customerReturn.class.php");
include_once ("customerReturnRpt.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {

	$page = new customerReturnRptPage();
	echo $page->render();
}
?>
