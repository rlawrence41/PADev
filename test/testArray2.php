<?php

//	Check Array behaviors.

$eol = "\n<br/>";
echo "<h1>Check Array Behaviors</h1>";

echo "<h2>Empty Array?</h2>";
$myArray = array();
echo "empty(array()): " . (empty($myArray) ? "true" : "false") . $eol ;

$myArray = "";
echo "is_array(\"\"): " . (is_array($myArray) ? "true" : "false") . $eol ;


?>