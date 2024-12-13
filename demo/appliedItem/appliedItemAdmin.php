<?php
include_once ("includes.php");
include_once ("appliedItem.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new appliedItemPage();
	echo $page->render();
}
?>
