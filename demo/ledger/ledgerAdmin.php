<?php
include_once ("includes.php");
include_once ("ledger.class.php");

// Make sure the user is authorized.
if (authorized(1000)) {
	$page = new ledgerPage();
	echo $page->render();
}
?>
