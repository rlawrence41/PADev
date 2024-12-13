<?php
include_once ("includes.php");
include_once ("purchaseOrder.class.php");

$table = new purchaseOrderTable();
echo $table->render();
