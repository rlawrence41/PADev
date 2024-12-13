<?php

/*
	updatePurchaseOrder.php updates the parent order record and re-processes the
	order based on the latest updates.  For example, changing the courier service
	is likely to change the shipping charge, and shipping address.

 */

include_once ("../includes.php");
include_once ("poCommon.php");




/******************************* Main Procedure ********************************************/

#debug_msg("Debugging saveShipTo: start.", true);


// Make sure the user is authorized.
if (authorized(1000)) {
	echo updateOrder();
}


?>