<?php
include_once ("includes.php");
include_once ("customerOrder.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new customerOrderPage();
	echo $page->render();
}
?>
