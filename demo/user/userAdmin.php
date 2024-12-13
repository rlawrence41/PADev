<?php
include_once ("includes.php");
include_once ("user.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new userPage();
	echo $page->render();
}
?>
