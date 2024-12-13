<?php
include_once ("includes.php");
include_once ("orders.class.php");

$table = new ordersTable();
echo $table->render();
