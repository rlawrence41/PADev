<?php

/*
	customerReturnWizard -- defines a wizard process to enter or edit a customer 
	order.
	
 */

include_once ("includes.php");
include_once ("customerReturnWizard.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new customerReturnWizard();
	echo $page->render();
	
	
}
	
?>