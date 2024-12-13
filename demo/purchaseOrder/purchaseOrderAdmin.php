<?php
include_once ("includes.php");
include_once ("purchaseOrder.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new purchaseOrderPage();
	echo $page->render();
}
?>
