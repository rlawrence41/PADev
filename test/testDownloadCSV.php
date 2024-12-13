<?php

/*
 *	testDownloadCSV.php -- This procedure should cause the sample.csv file to be 
 *	downloaded to the client machine.
 */

	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename="/downloads/sample.csv"');
	header('Pragma: no-cache');
	readfile("/var/www/ccgopvt/downloads/sample.csv");

?>