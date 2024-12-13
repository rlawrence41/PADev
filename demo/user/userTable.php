<?php
include_once ("includes.php");
include_once ("user.class.php");

$table = new userTable();
echo $table->render();
