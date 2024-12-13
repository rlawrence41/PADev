<?php
include_once ("includes.php");
include_once ("courier.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new courierPage();
	echo $page->render();
}
?>
