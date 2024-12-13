<?php
include_once ("includes.php");
include_once ("inventory.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new inventoryPage();
	echo $page->render();
}
?>
