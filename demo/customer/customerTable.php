<?php
include_once ("includes.php");
include_once ("customer.class.php");

$table = new customerTable();
echo $table->render();
