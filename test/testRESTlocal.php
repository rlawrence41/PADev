<?php

//	Test a local (server) call to the REST API.
//	(i.e. without an HTTP request.)

include ("includes.php");

// Establish a REST interface to query for data from this server.
include_once ("request.class.php");
include_once ("contactRequest.class.php");
$REST = new contactRequest();
dumpContext();
//$REST->column['state_abbr']='VT';
//$REST->orderBy = "last_name";

// Parse the request.
$REST->parseRequest();
$result = $REST->executeSQL();

// Results should be available for a GET request.
if ($REST->method == "GET" && is_array($result)){
	// Encode the results to JSON.
	$json = json_encode($result);

	// Respond with the results.
	header('Content-Type: application/json');
	echo $json;
}


function dumpContext() {
	global $REST;
	$eol = "\n";
	// Dump the context for the current request...
	echo "CONTEXT:" . $eol;
	echo "	Page: " . $REST->pageNo . $eol;
	echo "	Limit: " . $REST->resultLimit . $eol;
	echo "	Sort By: " . $REST->orderBy . $eol;
	echo "	filter: " . $eol;
	foreach ($REST->column as $fieldName => $fieldVal) {
		echo "		{$fieldName} = {$fieldVal}" . $eol;
	}
	echo "________________________________________________________________________________" . $eol;

}