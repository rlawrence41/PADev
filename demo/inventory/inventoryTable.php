<?php
include_once ("includes.php");
include_once ("inventory.class.php");

$table = new inventoryTable();
echo $table->render();
