<?php
include_once ("includes.php");
include_once ("receipt.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new receiptPage();
	echo $page->render();
}
?>
