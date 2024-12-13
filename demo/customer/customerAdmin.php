<?php
include_once ("includes.php");
include_once ("customer.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new customerPage();
	echo $page->render();
}
?>
