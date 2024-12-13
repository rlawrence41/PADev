<?php
include_once ("includes.php");
include_once ("receiptDetail.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new receiptDetailPage();
	echo $page->render();
}
?>
