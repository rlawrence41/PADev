<?php
include_once ("includes.php");
include_once ("itemTotal.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new itemTotalPage();
	echo $page->render();
}
?>
