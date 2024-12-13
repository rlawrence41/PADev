<?php
include_once ("includes.php");
include_once ("payment.class.php");

$table = new paymentTable();
echo $table->render();
