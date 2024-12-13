<?php
include_once ("includes.php");
include_once ("customerOrder.class.php");

$table = new customerOrderTable();
echo $table->render();
