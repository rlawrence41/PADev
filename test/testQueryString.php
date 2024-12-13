<?php

$fgc = file_get_contents("php://input");
$rf = readfile("php://input");
$aGet = $_GET;
$get = http_build_query($_GET);
$column = $_GET['column'];

// Drop a couple of array elements.
$bGet = $aGet;
$resource = $bGet['resource'];
unset($bGet['resource']);
$keyField = $bGet['keyField'];
unset($bGet['keyField']);
$queryStr = http_build_query($bGet);

?>


<html>
<body>
<h1>Test Query String</h1>

<p>
Using file_get_contents("php://input"): <?php echo $fgc; ?>
</p>

<p>
Using readfile("php://input"): <?php echo $rf; ?>
</p>

<p>
$_GET: <?php print_r($aGet); ?>
</p>

<p>
http_build_query($_GET): <?php echo $get; ?>
</p>

<p>
$column: <?php print_r($column); ?>
</p>

<p>
http_build_query(bGet): <?php echo $queryStr; ?>
</p>

