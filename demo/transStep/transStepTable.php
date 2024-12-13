<?php
include_once ("includes.php");
include_once ("transStep.class.php");

$table = new transStepTable();
echo $table->render();
