<?php
include_once ("includes.php");
include_once ("customerReturn.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new customerReturnPage();
	echo $page->render();
}
?>
