<?php 
include("includes.php");
$eol = "<br/>\n";
$dsn = $GLOBALS['DSN'] ;
echo "DSN: {$dsn}" . $eol ;

$connection = odbc_connect($dsn, '', '');

// Build the SQL query in segments.
$sqlArray = array();
$sqlArray['fields'] = "SELECT count(contact_no)" ;
$sqlArray['from'] = "FROM contact";
#$sqlArray['where'] = "WHERE upper(city) like 'ESS%'";
$sqlArray['where'] = "";
$$sqlArray['orderBy'] = "ORDER BY company, last_name, first_name";

// Make the query to count the total number of records.
$sql1 = implode(" ",$sqlArray);

// Now, simulate the page navigation selection...
$sqlArray['fields'] = "SELECT contact_no, contact_id, first_name, last_name, company, email";

// Get the page number from the request if provided.
if (isset($_GET['page'])) {$pageNo = intval($_GET['page']);}
else {$pageNo = 1;}
$perPage = 10;
$startRow = ($pageNo - 1) * $perPage;
$sqlArray['page'] = "LIMIT {$perPage} OFFSET {$startRow}";

// Formulate SQL query for the requested page. 
$sql2 = implode(" ",$sqlArray);


if (!$connection) { exit("Connection Failed:" . odbc_errormsg() ); }
#$sql = "select contact_no, contact_id, first_name, last_name, company, email from contact where upper(last_name) like 'SMI%' order by company, last_name, first_name limit {$perPage} offset {$startRow}";
$result = odbc_exec($connection, $sql2);
if ($result) { $contactCount = odbc_num_rows($result); }

$pageRows = array();

// Gather each row as an array into the result array.
while($row = odbc_fetch_array($result)){
	$pageRows[] = $row ;
}

$result = odbc_exec($connection, $sql1);
if (odbc_fetch_row($result)) {
	echo "<h3>Total potential record count:</h3>\n";
	$count = odbc_result($result, 1);
	echo number_format($count);
}
odbc_close($connection);

?>



<html><body>
<h1>Testing ODBC</h1>
<p>This page connects to an ODBC DSN.  </p>

<h2>SQL Command to gather count</h2>
<p><?php echo $sql1?></p>

<p>If successful, a message for the number of records
retrieved will be presented.  If not, an error message will be presented.</p>
<p><?php echo number_format($contactCount) . " records were retrieved." . $eol; ?></p>
<p>&nbsp;</p>


<p>This procedure was designed to select a subset of records from the result of the query.
This should show whether ODBC supports fetching rows by row number.
</p>

<h2>SQL Command to gather page records:</h2>
<p><?php echo $sql2; ?></p>

<h2>Add a page parameter to the URL to fetch a particular page.</h2>
<p>e.g. testODBC4.php?page=20</p>

<h2>Page Rows: </h2>
<pre>
<?php var_dump($pageRows); ?>
</pre>
</body></html>



