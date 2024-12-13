<?php
include_once ("includes.php") ;
includeResource("receipt") ;

// Make sure the user is authorized.
if (authorized(1000)) {

	$widget = new widget("receipt", "id", "Receipt", "Select or enter a payment received from the customer.") ;
	echo $widget->render();

}
?>