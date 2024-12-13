<?php
include("includes.php");
function testGlobals() {
    $foo = "local variable";

    echo '$foo in global scope: ' . $GLOBALS["foo"] . "\n";
    echo '$foo in current scope: ' . $foo . "\n";
}

$foo = "Example content";
testGlobals();
//print_r($GLOBALS);


?>

<!DOCTYPE html>
<html>
<body>

<h1>Testing PHP $_GLOBALS</h1>
<table cellpadding="10">

<?php 
foreach($GLOBALS as $varName => $value) {
	ob_start();
	var_dump($value);
	$varDump = ob_get_clean();
	echo <<<tableRow
<tr>
	<td style="vertical-align:top">\$GLOBALS['{$varName}']</td>
	<td style="vertical-align:top">
	  <pre>
{$varDump}
	  </pre>
	</td>
</tr>
tableRow ; 
} 

?>
</table>
<p><a href="/">Back to the Test menu</a></p>
</body>
</html>