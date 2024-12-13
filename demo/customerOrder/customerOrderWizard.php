<?php

/*
	customerOrderWizard -- defines a wizard process to enter or edit a customer 
	order.
	
 */

include_once ("includes.php");
include_once ("customerOrderWizard.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new customerOrderWizard();
	echo $page->render();
	
	
}
	
?>