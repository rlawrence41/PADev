<?php
include_once ("includes.php");
include_once ("unpaidOrder.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new unpaidOrderPage();
	echo $page->render();
}
?>
