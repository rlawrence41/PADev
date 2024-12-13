<?php

/*
	receiptDetailWizard -- defines a wizard process to enter or edit a customer 
	receipt.
	
 */

include_once ("includes.php");
include_once ("receiptDetailWizard.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new receiptDetailWizard();
	echo $page->render();
	
}
	
?>