<?php
include_once ("includes.php") ;
includeResource("orders") ;

// Make sure the user is authorized.
if (authorized(1000)) {

	$widget = new widget("orders", "id", "Customer Order", "Select or enter a customer order.") ;
	$widget->scripts[] = "ui/js/orders.js";
	echo $widget->render();

}
?>