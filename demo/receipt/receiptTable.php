<?php
include_once ("includes.php");
include_once ("receipt.class.php");

$table = new receiptTable();
echo $table->render();
