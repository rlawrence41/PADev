<?php
include_once ("includes.php");
include_once ("payment.class.php");
include_once ("paymentRpt.class.php");


// Make sure the user is authorized.
if (authorized(1000)) {

	$page = new paymentRptPage();
	echo $page->render();
}
?>
