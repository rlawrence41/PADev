<?php

/*
	purchaseOrderWizard -- defines a wizard process to enter or edit a customer 
	order.
	
 */

include_once ("includes.php");
include_once ("purchaseOrderWizard.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new purchaseOrderWizard();
	echo $page->render();
	
	
}
	
?>