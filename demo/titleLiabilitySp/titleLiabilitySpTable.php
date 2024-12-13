<?php
include_once ("includes.php");
include_once ("titleLiabilitySp.class.php");

$table = new titleLiabilitySpTable();
echo $table->render();
