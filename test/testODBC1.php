<?php 
include("includes.php");
$eol = "<br/>\n";
$dsn = $GLOBALS['DSN'] ;
echo "DSN: {$dsn}" . $eol ;

echo "SQL_CUR_USE_IF_NEEDED:  ";
var_dump(SQL_CUR_USE_IF_NEEDED);
echo $eol;
echo "SQL_CUR_USE_ODBC:  ";
var_dump(SQL_CUR_USE_ODBC);
echo $eol;
echo "SQL_CUR_USE_DRIVER:  ";
var_dump(SQL_CUR_USE_DRIVER);
echo $eol;

$connection = odbc_connect($dsn, '', '', SQL_CUR_USE_ODBC);
if (!$connection) { exit("Connection Failed:" . odbc_errormsg() ); }
$sql = "select count(contact_no) as cCount from contact";
$result = odbc_exec($connection, $sql);
while(odbc_fetch_row($result)){
#	echo number_format(odbc_num_rows($result)) . " records were retrieved.";
	$contactCount = odbc_result($result, "cCount");
}


odbc_close($conection);
?>

<html><body>
<h1>Testing ODBC</h1>
<p>This page attempts to investigate the ODBC cursor types.  </p>
<p>If successful, a message for the number of records
retrieved will be presented.  If not, an error message will be presented.</p>
<p>&nbsp;</p>
<h2>

<?php 
	echo number_format($contactCount) . " records were retrieved.";
?>

</h2>
</body></html>



