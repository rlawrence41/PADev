<?php
include_once ("includes.php");
include_once ("appliedOrder.class.php");

$table = new appliedOrderTable();
echo $table->render();
