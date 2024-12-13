<?php
include_once ("includes.php");
include_once ("customerReturnWizard.class.php");

$table = new customerReturn();
echo $table->render();
