<?php
include_once ("includes.php");
$CSV = new CSVexport("contact", "contact_no", "column[city]=ESSEX", "last_name");
$CSV->render();
?>
