<?php
include_once ("includes.php");
include_once ("customerOrderWizard.class.php");

$table = new customerOrder();
echo $table->render();
