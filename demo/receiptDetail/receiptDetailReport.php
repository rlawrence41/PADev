<?php
include_once ("includes.php");
include_once ("receiptDetail.class.php");
include_once ("receiptDetailRpt.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {

	$page = new receiptDetailRptPage();
	echo $page->render();
}
?>
