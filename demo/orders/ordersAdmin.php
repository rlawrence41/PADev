<?php
include_once ("includes.php");
include_once ("orders.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new ordersPage();
	echo $page->render();
}
?>
