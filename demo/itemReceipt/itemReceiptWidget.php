<?php
include_once ("includes.php") ;
includeResource("itemReceipt") ;

// Make sure the user is authorized.
if (authorized(1000)) {

	$widget = new widget("itemReceipt", "id", "Item Receipts", "Apply funds to the items paid for by this receipt.") ;
	echo $widget->render();

}
?>