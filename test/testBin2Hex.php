<?php
session_start();
echo "<h1>test Bin2Hex()</h1>\n";
if (isset($_GET['securityCode'])) {
	$submittedCode = $_GET['securityCode'];
	$securityCode = $_SESSION['securityCode'];
	echo "\n<h2>Submitted Code</h2>\n";
	var_dump($submittedCode);
	echo "\n<h2>Code Comparison</h2>\n";
	if ($submittedCode == $securityCode){echo "They match!\n";}
	else {echo "no match.  :-(\n";}
	

}
else {
	$securityCode = bin2hex(random_bytes(4));
	$_SESSION['securityCode'] = $securityCode;
}
echo "\n<h2>Security Code</h2>\n";
var_dump($securityCode);


?>