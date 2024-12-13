<?php
include_once ("includes.php");
include_once ("transStep.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new transStepPage();
	echo $page->render();
}
?>
