<?php 
include("includes.php");
$eol = "<br/>\n";
#$dsn = $GLOBALS['DSN'] ;
#$dsn = 'Driver={MariaDB Unicode};SERVER=localhost;DATABASE=pubassistDemo';
$dsn = 'pubassistDemo';

$user='www-data';
$password='PAWebUser';
echo "DSN: {$dsn}" . $eol ;

$connection = odbc_connect($dsn, '', '');
#$connection = odbc_connect($dsn, $user, $password);

if (!$connection) { exit("Connection Failed:" . odbc_errormsg() ); }
$sql = "select count(contact_no) as cCount from contact";
$result = odbc_exec($connection, $sql);
while(odbc_fetch_row($result)){
#	echo number_format(odbc_num_rows($result)) . " records were retrieved.";
	$contactCount = odbc_result($result, "cCount");
	
}

//if (!$connection) { exit("Connection was lost:" . odbc_errormsg() ); }

odbc_close($connection);
?>

<html><body>
<h1>Testing ODBC</h1>
<p>This page connects to ODBC DSN: <?php echo $dsn; ?></p>
<p>If successful, a message for the number of records
retrieved will be presented.  If not, an error message will be presented.</p>
<p>&nbsp;</p>
<h2>
	<?php echo number_format($contactCount) . " records were retrieved."; ?>
</h2>
</body></html>



