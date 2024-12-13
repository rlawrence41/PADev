<?php

/*
	receiptDetailWizard -- defines a wizard process to manage receiptDetail 
							transactions.
	
 */

include_once ("includes.php");
include_once ("receiptDetailWizard.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new receiptDetailWizard();
	echo $page->render();
	
}
	
?>