<?php
include_once ("includes.php") ;
includeResource("contact") ;

// Make sure the user is authorized.
if (authorized(1000)) {

	$widget = new widget("contact", "id", "Ship To", "Select or enter a ship to contact.") ;
	echo $widget->render();

}
?>