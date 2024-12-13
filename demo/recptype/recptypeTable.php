<?php
include_once ("includes.php");
include_once ("recptype.class.php");

$table = new recptypeTable();
echo $table->render();
