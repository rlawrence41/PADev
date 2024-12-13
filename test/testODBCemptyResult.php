<?php 
$connection = odbc_connect('pubassist', '', '');
if (!$connection) { $errorMessage = "Connection Failed:" . odbc_errormsg(); }

$sql = "select contact_no, contact_id, last_name, first_name, email, password from contact where contact_id LIKE 'ANOTHERUSER%'  order by contact_id,last_name,first_name,email";

$results = odbc_exec($connection, $sql);

if (!$results) {
	$message = "SQL Execution Failed: "; 
	$message .= odbc_errormsg($dbid);
	$errorMessage->postError($message);  	
}
else{
	$rowCount = odbc_num_rows($results);

	// Gather each row as an array into the result array.
	$rowNum = 0;
	while($row = odbc_fetch_array($results)){
		$resultArray[$rowNum] = $row;
		$rowNum++;
	}
}
odbc_close($conection);
?>


<html><body>
<h1>Testing ODBC</h1>
<p>This page connects to an ODBC DSN.  </p>
<p>If successful, a message for the number of records
retrieved will be presented.  If not, an error message will be presented.</p>
<p>&nbsp;</p>
<h2>

<p>Row count after executing SQL command: <?php echo strval($rowCount) ; ?></p>
<p>Result Array Count: <?php echo strval(count($resultArray)) ; ?></p>

</h2>
</body></html>



