<?php
include_once ("includes.php");
include_once ("receiptDetail.class.php");

$table = new receiptDetailTable();
echo $table->render();
