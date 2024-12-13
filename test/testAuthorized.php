<?php
include_once ("includes.php");

$eol = "<br/>\n";
echo "<h1>Testing Authorized()</h1>" . $eol;
echo "<p>Be sure to execute: " . $GLOBALS['protocol'] . $_SERVER['HTTP_HOST'] . "/../demo/login.php, first.</p>" . $eol;

// Make sure the user is authorized.
$response = authorized(500);

echo ($response ? "Logged in" : "Not Logged in") . $eol;
#echo "User:  " . $_SESSION['auth']['user']  . $eol;
var_dump($_SESSION['auth']);
echo $eol;
echo $eol;
echo "<h2>Trace Fields</h2>" . $eol;

$jsonData = json_encode([
	"updatedBy" => $_SESSION['auth']['user_id'], 
	"userNo" => $_SESSION['auth']['id'], 
	"lastUpdated" => date('Y-m-d H:i:s')
]);
echo var_dump($jsonData);

?>