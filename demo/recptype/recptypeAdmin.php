<?php
include_once ("includes.php");
include_once ("recptype.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new recptypePage();
	echo $page->render();
}
?>
