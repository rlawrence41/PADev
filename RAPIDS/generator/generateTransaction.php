<?php
/*

GenerateTransaction.php -- Similar to the application generator, this procedure 
		attempts to generate a template for a tranaction application.  
		
		A transaction application is a wizard application using the RAPIDS framework
		that manages a transaction by providing wizard steps to manage each supporting
		resource in a transaction.  
		
		This procedure depends on the transaction steps being defined in the database
		table, transStep.  The more complete the steps can be defined, the more 
		complete the application will be.
		
		The transaction resource is also needed as a parameter in the query string.
		(e.g. ".../generateTransaction.php?resource=receiptDetail").

 */

include_once ("../includes.php");


// Checks if a folder exist and returns the absolute pathname.
function folderExists($folder) {

    // Get canonicalized absolute pathname
    $path = realpath($folder);

    // If it exist, check if it's a directory
    return ($path !== false AND is_dir($path)) ? $path : false;

}

// This procedure generates all modules needed for the Admin application for a resource.
function genApplication($resourceName) {

	$eol = "<br/>\n";
	$templatePath = dirname(__file__) . '/template' ;

	// Page level applications
	$template = $templatePath . "/templateWizard.php" ;
	$destPath = dirname(__file__) . "/{$resourceName}/{$resourceName}Wizard.php" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;

	// Get the wizard steps to build the application.
	$steps = array();
	$steps = getSteps($resourceName);

	// Wizard Application UI classes
	$template = $templatePath . "/ui/classes/templateWizard.class.php" ;
	$destPath = dirname(__file__) . "/{$resourceName}/ui/classes/{$resourceName}Wizard.class.php" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;

	// The parent resource should be the resource for the first step in the wizard.
	$parentResource = $steps[0]["resource"];
	genModule("<<parentResource>>", $parentResource, $destPath, $destPath) ;

#var_dump($steps);
#echo $eol;

	// Generate the code for each step and incorporate into the same destination module.
	$template = $templatePath . "/ui/classes/templateWizardStep.php" ;
	$stepCode = genSteps($template, $steps);
	genModule("<<wizardSteps>>", $stepCode, $destPath, $destPath) ;

	// Generate a javascript shell for the transaction.
	$template = $templatePath . "/ui/js/template.js" ;
	$destPath = dirname(__file__) . "/{$resourceName}/ui/js/{$resourceName}.js" ;

echo "The transaction resource is:  " . $resourceName . $eol;

	genModule("<<resource>>", $resourceName, $template, $destPath) ;

echo "The Parent Resource is:  " . $parentResource . $eol;
	genModule("<<parentResource>>", $parentResource, $destPath, $destPath) ;


	echo "Generated: " . date("Y-m-d H:i:s");

}


// Generates the steps for the transaction wizard.
function genSteps($template, $steps) {

	$eol = "<br/>\n";
	$stepCode = "";

	// Each record in the result is for a wizard step.
	foreach ($steps as $stepDetail){

		// Get the step content from the template file.
		$content = file_get_contents($template, FILE_USE_INCLUDE_PATH);
	
		// Each step has several properties to be set.
		foreach($stepDetail as $propertyName => $propertyValue) {
		
			// Turn the property name into a token.
			$token = "<<" . trim($propertyName) . ">>";

			// Replace token with the submitted replacement string.
			$content = str_replace($token, $propertyValue, $content);

		}

		// Accumulate the step code.
		$stepCode .= $content;
	}
	return $stepCode ;

}


// Returns an array of column names for the resource.
function getSteps($txView) {
	
	$eol = "<br/>\n";
	$dsn = $GLOBALS['DSN'] ;
	echo "DSN: {$dsn}" . $eol ;

	$connection = odbc_connect($dsn, '', '');
	if (!$connection) { exit("Connection Failed:" . odbc_errormsg() ); }

	// Look for the transaction view used.
	$sql = "select * from transStep WHERE txView = '{$txView}'";
echo "Gathering steps: " . $sql .$eol;
	$result = odbc_exec($connection, $sql);
#	if (!$result) {return $this->postSQLError($dbid, $sql);}

	$rowNum = 0;
	$rowCount = odbc_num_rows($result);
echo "There are " . strval($rowCount) . " steps in this wizard.";
echo $eol;

	$resultArray = array();  // To hold the results for the request;

	// Gather each row as an array into the result array.
	while($row = odbc_fetch_array($result)){
		$resultArray[$rowNum] = $row;
#print_r($resultArray[$rowNum]);
#echo $eol;
		$rowNum++;
	}

	odbc_close($connection);

	return $resultArray ;
}




// Creates a folder at the given path.
function genFolder($path) {

	$eol = "<br/> \n" ;

	if (!folderExists($path)) { 
		echo "There is currently no folder for, '{$path}'...  "; 
		if (mkdir($path, 0774, true)) {
			echo "Successfully created {$path}." . $eol ; 
		}
		else { 
			echo "Failed to create folder: {$path}" . $eol ;
			return false;
		}
	}
	else { 
		echo "Folder, {$path}, already exists." . $eol ;	
	}
	return true ;
}


// Builds a folder structure for the application, within the Generator folder.
function genFolderStructure($resourceName) {

	$thisPath = dirname(__file__) . '/' ;
	$path = $thisPath . $resourceName ;
	if (!genFolder($path)) return false; 
	$path = $thisPath . $resourceName . '/rest/templates';
	if (!genFolder($path)) return false; 
	$path = $thisPath . $resourceName . '/ui/classes';
	if (!genFolder($path)) return false; 
	$path = $thisPath . $resourceName . '/ui/css';
	if (!genFolder($path)) return false; 
	$path = $thisPath . $resourceName . '/ui/js';
	if (!genFolder($path)) return false; 
	$path = $thisPath . $resourceName . '/ui/templates';
	if (!genFolder($path)) return false; 

	return true ;
}


// Generates the submitted module for the application based on a template.
function genModule($token, $replaceStr, $template, $destPath) {

	$eol = "<br/> \n" ;
	echo "Template path:  " . $template . $eol ; 
	echo "Destination path:  " . $destPath . $eol ;
	
	// Get content from the template file.
	$content = file_get_contents($template, FILE_USE_INCLUDE_PATH);

	// Replace token with the submitted replacement string.
	$content = str_replace($token, $replaceStr, $content);

	// Write the contents back to the file
	file_put_contents($destPath, $content);		
}


// Returns an array of column names for the resource.
function getColumns($resourceName) {
	
	$eol = "<br/>\n";
	$dsn = $GLOBALS['DSN'] ;
	echo "DSN: {$dsn}" . $eol ;
	$columns = array();

	$connection = odbc_connect($dsn, '', '');
	if (!$connection) { exit("Connection Failed:" . odbc_errormsg() ); }

	// Get the first record.
	$sql = "select * from {$resourceName} limit 1";
	$result = odbc_exec($connection, $sql);

	// Capture the column names from an associative array for the row.
	if ($result) {
		$row = odbc_fetch_array($result) ;
		$columns = array_keys($row) ;
	}

	odbc_close($connection);
	return $columns ;
}


/*********************************************************************/

// Make sure the user is authorized.
// Removed authorization.  Implemented password protection on the 
// directory instead.
#if (!(authorized(2000)))  return false ; 

// Obtain the name of the new resource from the query string.
$resourceName = $_GET["resource"];
if (empty($resourceName ))  return false ; 
	
// Create the folder structure for the application.
if (!genFolderStructure($resourceName))  return false;

// Use the template modules to generate the application for the 
// submitted resource.
genApplication($resourceName);


?>
<html>
<body>
<h1>Transaction Application Generator</h1>

</body>
</html>