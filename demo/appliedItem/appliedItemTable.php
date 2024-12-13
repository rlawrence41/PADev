<?php
include_once ("includes.php");
include_once ("appliedItem.class.php");

$table = new appliedItemTable();
echo $table->render();
