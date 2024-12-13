<?php
include_once ("includes.php") ;
includeResource("orderItem") ;

// Make sure the user is authorized.
if (authorized(1000)) {

	$widget = new widget("orderItem", "id", "Order Items", "Enter the items for this order.") ;
	echo $widget->render();

}
?>