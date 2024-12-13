<?php
include_once ("includes.php");
include_once ("orderItem.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new orderItemPage();
	echo $page->render();
}
?>
