<?php
include_once ("includes.php");
include_once ("stateTaxRate.class.php");

$table = new stateTaxRateTable();
echo $table->render();
