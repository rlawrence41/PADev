<?php
include_once ("includes.php");
include_once ("appliedOrder.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new appliedOrderPage();
	echo $page->render();
}
?>
