<?php
include_once ("includes.php");
include_once ("orderReceipt.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new orderReceiptPage();
	echo $page->render();
}
?>
