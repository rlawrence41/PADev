<?php

/*
	<<resource>>Wizard -- defines a wizard process to manage <<resource>> transactions.
	
 */

include_once ("includes.php");
include_once ("<<resource>>Wizard.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new <<resource>>Wizard();
	echo $page->render();
	
}
	
?>