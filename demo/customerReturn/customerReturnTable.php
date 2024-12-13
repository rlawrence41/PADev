<?php
include_once ("includes.php");
include_once ("customerReturn.class.php");

$table = new customerReturnTable();
echo $table->render();
