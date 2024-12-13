<?php
include_once ("includes.php");
include_once ("purchaseOrder.class.php");
include_once ("purchaseOrderRpt.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {

	$page = new purchaseOrderRptPage();
	echo $page->render();
}
?>
