<?php
include_once ("includes.php");
include_once ("localTaxRate.class.php");

$table = new localTaxRateTable();
echo $table->render();
