<?php
include_once ("includes.php");
include_once ("stateTaxRate.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new stateTaxRatePage();
	echo $page->render();
}
?>
