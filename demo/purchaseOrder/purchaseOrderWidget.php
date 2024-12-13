<?php
include_once ("includes.php");
include_once ("purchaseOrderWizard.class.php");

$table = new purchaseOrder();
echo $table->render();
