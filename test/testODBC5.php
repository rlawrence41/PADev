<?php 
include("includes.php");
$eol = "<br/>\n";
$dsn = $GLOBALS['DSN'] ;
echo "DSN: {$dsn}" . $eol ;

$connection = odbc_connect($dsn, '', '');
if (!$connection) { exit("Connection Failed:" . odbc_errormsg() ); }

// Count the total number of records.
$sql1 = "SELECT count(contact_no) as ccount FROM contact" ;
$result = odbc_exec($connection, $sql1);
if ($result) { 
	$row = odbc_fetch_array($result) ;
	$contactCount = $row['ccount'];
	print_r($row) ;
}

// Now get the first record.
$sql2 = "select * from contact limit 1";
$result = odbc_exec($connection, $sql2);
$row = odbc_fetch_array($result) ;
$columns = array_keys($row) ;
$columnsStr = implode(", ", $columns);

odbc_close($connection);

?>



<html><body>
<h1>Testing ODBC</h1>
<p>Show columns for the resource.  </p>

<h2>SQL Command to gather count</h2>
<p><?php echo $sql1?></p>

<p>If successful, a message for the number of records
retrieved will be presented.  If not, an error message will be presented.</p>
<p><?php echo number_format($contactCount) . " records were retrieved." . $eol; ?></p>
<p>&nbsp;</p>


<p>This procedure was designed to select a single record to show the column names.</p>

<h2>SQL Command to gather page records:</h2>
<p><?php echo $sql2; ?></p>

<h2>Columns: </h2>
<p>
<?php echo $columnsStr; ?>
</p>
</body></html>



