<?php
include_once ("includes.php");
include_once ("code.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new codePage();
	echo $page->render();
}
?>
