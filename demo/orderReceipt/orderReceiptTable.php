<?php
include_once ("includes.php");
include_once ("orderReceipt.class.php");

$table = new orderReceiptTable();
echo $table->render();
