<?php

/*
	saveReceipt.php updates the parent order record and re-processes the
	receipt based on the latest updates.  

 */

include_once ("../includes.php");
include_once ("rdCommon.php");




/******************************* Main Procedure ********************************************/

#debug_msg("Debugging saveShipTo: start.", true);


// Make sure the user is authorized.
if (authorized(1000)) {
	echo updateReceipt();
}


?>