<?php
include_once ("includes.php");
include_once ("<<resource>>.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new <<resource>>Page();
	echo $page->render();
}
?>
