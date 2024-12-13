<?php
include_once ("includes.php");
include_once ("title.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new titlePage();
	echo $page->render();
}
?>
