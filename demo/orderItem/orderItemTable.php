<?php
include_once ("includes.php");
include_once ("orderItem.class.php");

$table = new orderItemTable();
echo $table->render();
