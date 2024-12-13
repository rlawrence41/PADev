<?php
include_once ("includes.php");
include_once ("itemReceipt.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new itemReceiptPage();
	echo $page->render();
}
?>
