<?php
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

	$templatePath = dirname(__file__) . '/template' ;

	// Page level applications
	$template = $templatePath . "/templateAdmin.php" ;
	$destPath = dirname(__file__) . "/{$resourceName}/{$resourceName}Admin.php" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;
	
	$template = $templatePath . "/templateTable.php" ;
	$destPath = dirname(__file__) . "/{$resourceName}/{$resourceName}Table.php" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;
	
	$template = $templatePath . "/templateSearchList.php" ;
	$destPath = dirname(__file__) . "/{$resourceName}/{$resourceName}SearchList.php" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;

	$template = $templatePath . "/includes.php" ;
	$destPath = dirname(__file__) . "/{$resourceName}/includes.php" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;

	// Application UI classes
	$template = $templatePath . "/ui/classes/template.class.php" ;
	$destPath = dirname(__file__) . "/{$resourceName}/ui/classes/{$resourceName}.class.php" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;
	
	// The UI classes need the resource columns.
	// Capture the column names for the resource.
	$columns = getColumns($resourceName);

	// Generate the list of table columns from the resource columns and 
	// incorporate into the same destination module.
	$columnList = genColumnsForTable($columns);
	genModule("<<tableColumns>>", $columnList, $destPath, $destPath) ;
	
	// Generate the list of form fields from the resource columns and 
	// incorporate into the same destination module.
	$fieldList = genColumnsForForm($columns);
	genModule("<<formFields>>", $fieldList, $destPath, $destPath) ;	
	
	// Application REST API
	$template = $templatePath . "/rest/template.php" ;
	$destPath = dirname(__file__) . "/{$resourceName}/rest/{$resourceName}.php" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;
	
	$template = $templatePath . "/rest/includes.php" ;
	$destPath = dirname(__file__) . "/{$resourceName}/rest/includes.php" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;

	// Application REST templates
	$template = $templatePath . "/rest/templates/template_COUNT.sql" ;
	$destPath = dirname(__file__) . "/{$resourceName}/rest/templates/{$resourceName}_COUNT.sql" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;

	$template = $templatePath . "/rest/templates/template_GET.sql" ;
	$destPath = dirname(__file__) . "/{$resourceName}/rest/templates/{$resourceName}_GET.sql" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;
	if (!empty($columnList)) {$columnList = implode(", ", $columns);}
	else {$columnList = "Table was not found" ;}
	
	// Add the column names to the REST SQL GET template.
	genModule("<<fieldList>>", $columnList, $destPath, $destPath) ;

	$template = $templatePath . "/rest/templates/template_PUT.sql" ;
	$destPath = dirname(__file__) . "/{$resourceName}/rest/templates/{$resourceName}_PUT.sql" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;

	$template = $templatePath . "/rest/templates/template_POST.sql" ;
	$destPath = dirname(__file__) . "/{$resourceName}/rest/templates/{$resourceName}_POST.sql" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;
	
	$template = $templatePath . "/rest/templates/template_DELETE.sql" ;
	$destPath = dirname(__file__) . "/{$resourceName}/rest/templates/{$resourceName}_DELETE.sql" ;
	genModule("<<resource>>", $resourceName, $template, $destPath) ;

}


// Generates the fields for the adminForm component.
function genColumnsForForm($columns) {
	// Sample: $this->addColumn("company","Company", "Company or organization name");
	$fieldList = "" ;
	foreach ($columns as $columnName) {
		$label = ucwords(str_replace("_", " ", $columnName)) ;
		$fieldStr = "		\$this->addField(\"text\", \"{$columnName}\", \"$label\");\n" ;
		$fieldList .= $fieldStr ;
	}
	return $fieldList ;	
}


// Generates the columns for the table component.
function genColumnsForTable($columns) {
	// Sample: $this->addColumn("company","Company", "Company or organization name");
	$columnList = "" ;
	foreach ($columns as $columnName) {
		$headerStr = ucwords(str_replace("_", " ", $columnName)) ;
		$columnStr = "		\$this->addColumn(\"{$columnName}\",\"{$headerStr}\");\n" ;
		$columnList .= $columnStr ;
	}
	return $columnList ;
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
<h1>Application Generator</h1>

</body>
</html>