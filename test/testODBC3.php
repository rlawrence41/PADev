<?php 
include("includes.php");
$eol = "<br/>\n";
$dsn = $GLOBALS['DSN'] ;
echo "DSN: {$dsn}" . $eol ;

$connection = odbc_connect($dsn, '', '');
if (!$connection) { exit("Connection Failed:" . odbc_errormsg() ); }
$sql = "select * from contact limit 100";
$result = odbc_exec($connection, $sql);

if ($result) { $contactCount = odbc_num_rows($result); }

odbc_close($connection);

?>


<html><body>
<h1>Testing ODBC</h1>
<p>This page connects to an ODBC DSN.  </p>
<p>If successful, a message for the number of records
retrieved will be presented.  If not, an error message will be presented.</p>
<p>&nbsp;</p>
<h2>Investigating the structure of the result set:</h2>

<?php 
	echo number_format($contactCount) . " records were retrieved." . $eol;
	print_r($result);
	echo $eol;
	var_dump($result);
	echo $eol;
?>

</body></html>



