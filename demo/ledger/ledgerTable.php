<?php
include_once ("includes.php");
include_once ("ledger.class.php");

$table = new ledgerTable();
echo $table->render();
