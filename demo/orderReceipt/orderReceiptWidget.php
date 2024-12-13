<?php
include_once ("includes.php") ;
includeResource("appliedOrder") ;

// Make sure the user is authorized.
if (authorized(1000)) {

	$widget = new widget("appliedOrder", "id", "Order Receipt", "Apply funds to selected orders.") ;
	echo $widget->render();

}
?>