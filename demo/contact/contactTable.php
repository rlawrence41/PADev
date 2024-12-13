<?php
include_once ("includes.php");
require_once ("contact.class.php");

$table = new contactTable();
echo $table->render();

?>
