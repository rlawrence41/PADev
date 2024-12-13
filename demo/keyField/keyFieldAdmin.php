<?php
include_once ("includes.php");
include_once ("keyField.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new keyFieldPage();
	echo $page->render();
}
?>
