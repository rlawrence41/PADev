<?php
include_once ("includes.php");
include_once ("localTaxRate.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new localTaxRatePage();
	echo $page->render();
}
?>
