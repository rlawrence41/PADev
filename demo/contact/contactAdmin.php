<?php
include_once ("includes.php");
include_once ("contact.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new contactPage();
	echo $page->render();
}
?>
