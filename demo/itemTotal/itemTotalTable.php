<?php
include_once ("includes.php");
include_once ("itemTotal.class.php");

$table = new itemTotalTable();
echo $table->render();
