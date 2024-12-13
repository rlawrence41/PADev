<?php 

// Example usage:
include("includes.php");
$eol = "<br/>\n";
$dsn = $GLOBALS['DSN'] ;
echo "DSN: {$dsn}" . $eol ;

$conn = odbc_connect($dsn, '', '');
if (!$conn) { exit("Connection Failed:" . odbc_errormsg() ); }

$dsn_info = odbc_data_source($conn, SQL_FETCH_FIRST);
while ($dsn_info) {
    print_r($dsn_info);
    $dsn_info = odbc_data_source($conn, SQL_FETCH_NEXT);
}

odbc_close($conn);
?>
<html><body>
<h1>Testing ODBC</h1>
<h2>This page uses a connection to present information about the Data Source.  </h2>

<p>&nbsp;</p>
<h2> </h2>
</body></html>



