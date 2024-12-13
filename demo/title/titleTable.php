<?php
include_once ("includes.php");
include_once ("title.class.php");

$table = new titleTable();
echo $table->render();
