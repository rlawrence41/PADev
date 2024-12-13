<?php
include_once ("includes.php") ;
includeResource("appliedItem") ;

// Make sure the user is authorized.
if (authorized(1000)) {

	$widget = new widget("appliedItem", "id", "Item Receipt", "Apply funds to selected items.") ;
	echo $widget->render();

}
?>