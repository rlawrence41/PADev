<?php
include_once ("includes.php");
include_once ("titleLiabilitySp.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new titleLiabilitySpPage();
	echo $page->render();
}
?>
