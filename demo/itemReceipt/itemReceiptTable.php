<?php
include_once ("includes.php");
include_once ("itemReceipt.class.php");

$table = new itemReceiptTable();
echo $table->render();
