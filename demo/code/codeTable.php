<?php
include_once ("includes.php");
include_once ("code.class.php");

$table = new codeTable();
echo $table->render();
