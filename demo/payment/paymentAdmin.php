<?php
include_once ("includes.php");
include_once ("payment.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new paymentPage();
	echo $page->render();
}
?>
