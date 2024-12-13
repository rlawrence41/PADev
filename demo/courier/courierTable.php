<?php
include_once ("includes.php");
include_once ("courier.class.php");

$table = new courierTable();
echo $table->render();
