<?php

/*
 *	testBuildSQL.php -- This procedure is intended to test the buildSQL method 
 *						of the REST Request class.  
 */

include "includes.php";
$eol = "\n<br/>";
echo "<h1>Test REST <i>buildSQL()</i> Method</h1>";

$REST = new contactRequest();
$REST->method = "GET";
$REST->searchVal = "Lawr";
$REST->setSearch("company,last_name,first_name,email");
print_r($REST->column);
echo $eol;
$REST->orderBy = "company,last_name,first_name,email";
$REST->parseRequest();
foreach ($REST->sql as $index=>$sql){ echo $sql . $eol; }
