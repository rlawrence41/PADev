<?php

/*	REQUEST.CLASS.PHP
 
 * The Request Class is an OOP component implemented in PHP.  This is the only logic that 
 * actually touches the database.  The __CONSTRUC module accepts the URL and parameters and 
 * sets up the parse for the request.
 
 * The REST request component works by first parsing the request according to the REST API 
 * and HTTP standards.
 
 * It then build a list of SQL commands to be executed.  Frequently, there may only be a
 * single command to be generated, but a POST or PATCH request may generate SQL commands 
 * for multiple submitted records.
 
 * Finally, the module executes the list of the SQL commands generated.
 
 * ________________________________________________________________________________________
 
 
 *
 *	June 17, 2019 -- This is a re-write of RESTapi.php using object-oriented programming.
 *	In addition to the change to OOP, some features have been added:
 *
 *		The ability to specify a sort order.
 *		The ability to specify a page number for pagination.
 *		The ability to use this component locally.
 *
 *	This is a library of routines used to implement REST compliant services.
 *	These procedures are reusable and intended to be included into php procedures that provide 
 *	those services.
 *
 *	CHANGE 2/12/2019 - There has been a major shift in the strategy for processing a request 
 *			in these procedures.  The current strategy is to parse the request into
 *			component SQL commands to affect the request.  A failure to parse the 
 *			request (including any posted data) will result in returning a failed 
 *			status.  Only after successful construction of that list of SQL commands
 *			can an attempt be made to execute the commands.  This should help to 
 *			support the concept of transaction processing (i.e. an all-or-nothing
 *			update to the database).
 *
 *	CHANGE 10/10/2019 - Prior to this change, the searchList was the only reason
 *			for a relaxed search.  It turns out to be desireable to loosen things
 *			up a bit when setting a filter.  However, a filter setting should 
 *			ONLY allow string values to be relaxed.  The filter condition should
 *			use the AND operator for multiple fields. 
 *	
 *	CHANGE 2/11/2019 - Major reorganization of routines used to build the SQL queries.  
 *			The object has been to simplify each routine to make the logic easier to 
 *			follow--and therefore to debug.  Essentially, the relaxed search caused 
 *			the logic of arrayToString() to be way overcomplicated.  This change 
 *			further separates functions within the buildSQL() sequence of functions.
 *
 *	CHANGE 11/26/2021 - Added a secondary key property to allow records to be updated 
 *			by secondary key.  Added the getSecondaryKey() function to process the new
 *			property for a POST or PATCH method.
 *
 */

$debug=false;

/*
 *	The Request class now contains all the functions needed to execute REST API requests.
 *	This calls will be subclassed for specific resources.  Each resource will have its
 *	own specific SQL templates.
 */

Class request{
	
	public $resource = "";	// Holds the name of the resource being requested.
								// 	This is generally a table name.
	public $error = "";			// Provide for reporting an error condition.
	public $keyValue=0;			// Holds the identifier for an instance of the resource.
	public $keyField="";		// Holds the name of the key field for the resource.
	public $secondaryKey=""; 	// Holds the list of fields that act as a secondary key.
	public $method="";			// Holds the method specified in the HTTP request.
	public $column=array();		// Holds the submitted 'column' attributes from the query string.
	public $postData = "";		// Holds any data that was posted.  Should be JSON.
	public $orderBy = "";		// Holds the desired order by expression.
	public $pageNo = 1;			// Holds the requested page number.
	public $limit = 0;			// Holds the max number of records to be retrieved.
	public $perPage = 10;		// Holds the number of records to return for rendering on a page.
	public $count = 0;			// Holds the total number of records in the result.
	public $sql = array();		// Holds a list of SQL commands for execution.  NULL to cause
								// 	initialization for the first command generated.
	public $methodSQL = array();// Holds the SQL template for supported request methods.
	public $compare = "tempered";// By default, filter conditions are loose but cumulative.
	public $searchVal = "";		// Holds a search value for populating a search list.
	public $response = "";		// The request may specify an alternate format for the response.
	
	public function __construct() {
		
		// Capture the HTTP method...
		$this->method = $_SERVER['REQUEST_METHOD'];
		debug_msg("RESTapi Submitted Method: " . $this->method, false, "Request::__construct()");

		if (isset($_GET['keyField'])) 	{$this->keyField = $_GET['keyField'];}

		// GET requests may specify a sort order and a page number.  These options
		// will appear in the query string parameters of the request.  

		// NOTE: There is NO query string for post requests.
		if (isset($_GET['sortBy'])) 	{$this->orderBy = $_GET['sortBy'];}
		if (isset($_GET['page'])) 		{$this->pageNo = $_GET['page'];}
		if (isset($_GET['limit'])) 		{$this->limit = $_GET['limit'];}
		if (isset($_GET['perPage'])) 	{$this->perPage = $_GET['perPage'];}

		// The following parameters support the searchList function.  
		// This marks a change in the model.  The REST API will be called
		// directly, to update the table or a list in the UI.
		if (isset($_GET['compare'])){$this->compare = $_GET['compare'];}
		if (isset($_GET['response'])){$this->response = $_GET['response'];}

		// The filter condition will appear in the column[] array in the query
		// string.
		if (isset($_GET['column'])){$this->column = $_GET['column'];}

		// Alternatively, a searchValue may be submitted to be used in 
		// multiple, but resource-specific fields.
		if (isset($_GET['searchVal'])){
			$this->searchVal = urldecode($_GET['searchVal']);
		}

	}


	/* 
	 *	addMethodSQL - This function adds the SQL template for the submitted request method.
	 *					(i.e. GET, POST, PUT, PATCH, or DELETE)
	 *					The additon of a SQL template to this property is an indication that
	 *					the method is supported in the API.  If the method SQL is blank or
	 *					not present, it indicates that the method is NOT supported.
	 */
	public function addMethodSQL($method, $templateFile){

		// Capture the SQL template for the method.
		debug_msg("Template File:  {$templateFile}", false, "addMethodSQL");
		debug_msg("Include Path:  " . get_include_path(), false);
		$sql = file_get_contents($templateFile, FILE_USE_INCLUDE_PATH);
		
		// Save it to the submitted method.
		$this->methodSQL[$method] = $sql;
	}


	// This adds a completed SQL command to the list in the request.
	private function addSQL($sql) {

		// Save the SQL command to the request.
		$next = count($this->sql);
		
		// The list is intialized to null.  
		// Re-initialize the array if this is the first SQL command.
		if ($next == 0) {$this->sql=array("");}	
		$this->sql[$next] = $sql;
		debug_msg("Adding SQL command: " . $sql);
		return true;

	}


	/*
	 *	ArrayToString -- Accepts an associative array and converts it to a 
	 *				string of names and values, useful for a SET or WHERE clause.
	 */
	private function arrayToString($input=array(), $glue=",", $operator="=", $includeFields=true) {

		// Make sure we have an array.
		if (!is_array($input) OR empty($input)){return "";}	// Nothing to process.

		// Process through each element and construct the name/value pair.
		$result = "";
		$method = $this->method;
		debug_msg("Request Method: " . $method, true, "ArrayToString()");

		// No operator if field names are not included.
		if (!$includeFields) $operator = "";


		foreach ($input as $fieldName=>$value) {

			// If the result string is not empty, add the glue...
			if (strLen($result) > 0) {$result .= $glue;}

			// Add the field name, if fields are included.  
			// Include the resource name to avoid 
			// ambiguity in queries with JOIN clauses.
			if ($includeFields) $result .= "{$this->resource}.{$fieldName}";
			
			// Numeric values should always use the equal operator.
			if (isNumberNotString($fieldName, $value)) {
				if ($includeFields) $operator = "=";
				$result .= $operator . strval($value);
			}
			
			// Null value should be tested with 'IS NULL' if executing a GET request.
			elseif (is_null($value) || strToUpper($value) == "NULL") {

				$operator = ($this->method == "GET") ? " IS " : $operator;
				$nullStr = $operator . 'NULL';
				$result .= $nullStr;

				debug_msg("Null String: " . $nullStr, true);

			}
			
			// Add the operator and value, but test for various conditions if 
			// retrieving data.  If storing, do not change the value.
			else {

				#debug_msg("Adding field: {$fieldName}, operator: {$operator} and Value: {$value} to result.", true);

				$result .= $operator ;
				$result .= checkValue($fieldName, $value, $operator);
//				$result .= ($this->method == "GET") ?
//						checkValue($fieldName, $value, $operator) : $value ;
			}
		}
		return $result;
	}


 /*
	 *	buildSQL - uses the properties of the request to build an appropriate SQL command.
	 *				If data is posted, this procedure accepts an optional input array.
	 *
	 *				This procedure uses the request method to select the SQL template.
	 *				It then substitutes the tokens in that template using the remaining 
	 *				properties of the request.
	 */

	public function buildSQL($input=array()){

		// Capture the template SQL command based on the request method.	
		$sql = $this->methodSQL[$this->method];

		debug_msg("SQL Template: " . $sql, true, "BuildSQL");

		// CHANGE:	2024-07-02, 2024-08-05
		// I have introduced a CALL command to execute a stored procedure into
		// the method templates.  
		// Look for a CALL command before processing individual methods.
		if (stripos($sql, "CALL ") !== false) {
			$sql = $this->buildSQLCall($sql); 
#			debug_msg("Found CALL to Stored Procedure: " . $sql, true);
			return $this->addSQL($sql);
		}
#		ELSE debug_msg("Did NOT find CALL to Stored Procedure: " . $sql, true);

		// Branch to special cases for each HTTP request method.
		switch ($this->method) {
		case "GET":	
			// Check for the ORDER BY and LIMIT clauses.
			$sql = $this->checkGetProperties($sql);
			break;
		case "PUT":
			$sql = $this->buildSQLInsert($sql, $input);
			break;	// A put operation does not require a key, since one will be assigned.
		case "DELETE":	
			$this->foundKeyValue($input); 
			break;
		default:	// POST or PATCH should generate a SQL UPDATE...
			// Change 2021-11-26:  Added a secondary key property to allow records
			//	to be updated by a secondary key.
			//  ALL update operations require a primary or secondary key.

			// Change 2022-11-29:  Since this procedure was modified to allow an update by
			//	secondary key, we don't know which method (or template) should be used
			//	until the getSecondaryKey() has returned.
			
			// Change 2024-12-04:  With the advent of transactions and the use of stored
			// procedures, it can no longer be assumed that POST and PATCH are the same.
			// The updateInstance() javascript procedure uses PATCH.  That is the default
			// method for updating a record, and so the default was changed to "PATCH" 
			// here.
			if ($this->getSecondaryKey($input)){
				if ($this->findBySecondaryKey() > 0) {
					$sql = $this->methodSQL["PATCH"];	// Just to make it explicit.
					$sql = $this->buildSQLUpdate($sql, $input); 
				}
				else {
					// We need the "PUT" SQL template.
					$sql = $this->methodSQL["PUT"];
					$sql = $this->buildSQLInsert($sql, $input);
				}
			}
		}
		
		// SUBSTITUTE TOKENS IN THE SQL TEMPLATE FOR THIS METHOD.
		
		// CHANGE 2024/07/31 -- Work on the receipts, apply funds applications,
		// I'm running into queries that use specific field values in places other
		// than the WHERE clause.  It occurs to me that if the template specifies
		// specific fields, they should show in the columns[] property.  So, I'm 
		// checking the SQL template for those values.  It will be up to The
		// specific application to make sure that the appropriate tokens are in the
		// templates.

		$sql = $this->buildSQLValueSubstitution($sql);

		// Queries that REQUIRE a key value will have a %key% token.
		// So, queries that REQUIRE a key value and don't have one will fail!
		if ($this->keyValue > 0){$sql = str_replace("%key%", $this->keyValue, $sql);}

		// Make sure there is a %where% token before bothering to build a WHERE clause.

		if (strpos($sql, '%where%') !== false) {

			// For some queries, the key value may BE the WHERE clause.
			// Otherwise, Check the column[] property to generate a WHERE clause...
			$whereClause = $this->buildWhere();
			debug_msg("Where Clause: " . $whereClause, true);
			$sql = str_replace("%where%", $whereClause, $sql);
		}

		// Substitute the resource token with the table name in the query.
		$sql = str_replace("%resource%", $this->resource, $sql);

		// Substitute the keyField token with the key field for this resource.
		$sql = str_replace("%keyField%", $this->keyField, $sql);

		// CHANGE:  DON'T TEST FOR UNUSED TOKENS! ///////////////////////////////
		// If there is a remaining token, I'd rather have it show up as an error.

		// Catch all... If this is a SQL update command, make sure it has a where clause.
		if (missingWhere($sql)){
			$message = "Update command has no where clause:  {$sql}";
			return $this->postError($message);
		}
	
		debug_msg("Generated SQL command: " . $sql, true, "BuildSQL");

		// Add the SQL command to the request.
		return $this->addSQL($sql);
	}


	/*	
	 *	buildSQLCall($sql:STRING, $column:ARRAY) - This function substitutes 
	 *					 field values into the submitted SQL string for a 
	 *					 CALL to a stored procedure.
	 */
	private function buildSQLCall($sql) {

		debug_msg("Method: {$this->method}", true, "buildSQLCall()");
		if ($this->method == "GET") {
			$parameters = implode(", ", $this->column);
		}
		else {
			
			// Parameters are in the posted data.
			// Posted data should be a JSON string.
			$this->postData = file_get_contents("php://input");	// Should be JSON.

			// Parameters are drawn from the posted data.
			$parameters = "";
			if ($this->postData > "") {

				$json = preg_replace('/[[:cntrl:]]/', '', $this->postData);
				$input = json_decode($json, true);

				// If so, does it look like JSON?
				if (!is_array($input)) {
				$message = "JSON Decode Error: " . json_last_error_msg();
					return $this->postError($message);
				}
				$parameters = implode(", ", $input);
			}
		}

		debug_msg("Parameters: {$parameters}", true, "buildSQLCall()");
		$sql = str_replace("%parameterList%", $parameters, $sql);
		if ($this->keyValue > 0) $sql = str_replace("%key%", $this->keyValue, $sql);
		
		return $sql;
	}


	/*	
	 *	buildSQLInsert($sql:STRING, $input:ARRAY) - This function substitutes 
	 *					 field names and values into the submitted SQL string for INSERT 
	 *					 operations.
	 */
	private function buildSQLInsert($sql, $input) {

		// Separate fields and values for an INSERT command...
		// Gather the field names alone for the FIELDS clause.
		$fieldList = implode(",", array_keys($input));
		$sql = str_replace("%fieldList%", $fieldList, $sql);
		
		// Suppress field names for the VALUES clause.
		// arrayToString() has grown too complicated.  I just want the values
		// from the $input array.  However, the values must be enclosed in 
		// quotes.
		$valuesList = $this->arrayToString($input, ",", "", false);
#		$valuesList = addQuotes(implode('","', array_values($input)));
		$sql = str_replace("%valuesList%", $valuesList, $sql);
		
		return $sql;
	}


	/*	
	 *	buildSQLUpdate($sql:STRING, $input:ARRAY) - This function substitutes field - value
	 *					pairs into the submitted SQL string for UPDATE operations.
	 */
	private function buildSQLUpdate($sql, $input) {

		// If processing an UPDATE command, use the posted data to build a SET clause.
		$set = $this->arrayToString($input, ",", "=");

		// Substitute the set clause.
		$sql = str_replace("%set%", $set, $sql);
		return $sql;
	
	}


	/*
	 *	buildSQLValueSubstitution - Substitutes column[] keys and values into the 
	 *					submitted SQL template.
	 *
	 *	NOTE: This procedure does NOT work when the key field value is used to 
	 *	requst a record.
	 */
	private function buildSQLValueSubstitution($sql) {
		
		debug_msg("Begin SQL:  {$sql}", false, "buildSQLValueSubstitution()");

		// Process through the submitted column names.
		foreach ($this->column AS $fieldName => $fieldVal) {
			$token = "%{$fieldName}%";
			$sql = str_replace($token, $fieldVal, $sql);
		}

		debug_msg("End SQL:  {$sql}");
		return $sql;
	}


	/*
	 *	buildWhere - generates a WHERE clause, either from the key field and value, or
	 *				from elements of the column[] property for the request.
	 */
	public function buildWhere(){
		
		$whereClause = "";
		
		// A key value trumps all.
		if ($this->keyValue > 0) {
			debug_msg("Key value provided: " . strval($this->keyValue));
			$whereClause = "where ";
			$whereClause .= $this->resource . "." . $this->keyField . "=" . strval($this->keyValue);
			return $whereClause;
		}

		// Check the column property for values for the Where clause.
		if (!empty($this->column)){

			if (isset($this->column['id'])) {
					$operator = "=" ;
					$glue = " AND ";
			}
			else {
			
			/* 	April 30, 2021 -- Adding another layer of conformance, "tempered" to the filter 
					condition. */
				switch ($this->compare) {
				case "strict":
					$operator = "=" ;
					$glue = " AND ";
					break;
				case "tempered":
					$operator = " LIKE " ;
					$glue = " AND ";
					break;
				case "relaxed":
					$operator = " LIKE " ;
					$glue = " OR ";
					break;
				default:
					$operator = "=" ;
					$glue = " AND ";
				}
			}
			// Use the arrayToString() function to create the WHERE clause.
			$whereClause = "where " . $this->arrayToString($this->column, $glue, $operator);	
			debug_msg("WHERE Clause " . $whereClause, true, "buildWhere()" );
		}
		return $whereClause;
	}


	/* 
	 *	checkGetProperties() - checks properties for clauses that are unique to a 
	 *						GET request.
	 */
	private function checkGetProperties($sql){

		if ($this->method == "GET") {
			
			// If there is an order by property set, then add an Order By clause.
			$orderByClause = "";
			if (!empty($this->orderBy)) {
				$orderByClause = " order by " . $this->orderBy ;
			}
			$sql = str_replace("%orderBy%", $orderByClause, $sql) ;
			
/*			Let the limit clause wait until execution.

 			// Check to see whether a page limit has been specified.
			$limitClause = "" ;
			if ($this->limit > 0) {
				$limitClause = "Limit " . strval($this->limit);
			}
			$sql = str_replace("%limit%", $limitClause, $sql) ;
 */
		}
		
		return $sql;
	}


	/*	dbClose - closes the database connection. */
	public function dbClose($dbid) {
		odbc_close($dbid);
		debug_msg("Closed database: " . $GLOBALS["DSN"], false, "dbClose") ;
	}

	/*	dbOpen() - opens the database connection. */
	public function dbOpen(){

//		$connection = odbc_connect('pubassist', '', '');
		debug_msg("Opening database: " . $GLOBALS["DSN"], false, "dbOpen") ;
		$connection = odbc_connect($GLOBALS["DSN"], '', '');
		if (!$connection) { exit("Connection Failed:" . odbc_errormsg() ); }
		return $connection;
	}


	/*
	 *	Function executeSQL performs the actual SQL query execution and return the results 
	 *	in an array.
	 */
	public function executeSQL()  {
	global $debug;

		if (empty($this->sql)){return $this->postError("There were no SQL commands to execute.");}


		// There can be several SQL commands to execute...
		foreach ($this->sql as $sql){

			debug_msg("Executing SQL command: ${sql}", true, "executeSQL()") ;

			$dbid = $this->dbOpen();

			// Separate GET requests.
			if ($this->method == "GET") {
				$result = $this->executeSQLGET($dbid, $sql);
			}

			// Otherwise, simply execute the SQL command.
			else {
				$result = odbc_exec($dbid,$sql) ;
				if (!$result) {$this->postSQLError($dbid, $sql);}
			}
		}

		$this->dbClose($dbid);		
		return $result ;
	} 


	/*
	 *	Function executeSQLGET executes the SQL SELECT queries and returns the 
	 *	results in an array.
	 */
	public function executeSQLGET($dbid, $sql)  {
	global $debug;
	
		$result = array();
		$records = $this->executeSQLGETRecords($dbid, $sql);
		if (is_array($records)){
			// Check for errors after execution...
			$result[0] = $this->executeSQLGETCount($dbid);
			if (!$result[0]) {return false;}
			$result[1] = $records;
			if (!$result[1]) {return false;}
		}

		return $result;
	}



	/*
	 *	Function executeSQLGETCount executes a related SQL SELECT query to capture the
	 *	count of records that satisfy the submitted criteria.  
	 *	The count is needed for pagination.
	 */
	public function executeSQLGETCount($dbid)  {
	global $debug;

		//	As long as we're going to the trouble, put together some summary information.
		$summary = array();
		$summary['count'] 	= 0;
		$summary['page'] 	= $this->pageNo;
		$summary['perPage']	= $this->perPage;


		// If the keyField was provided, then the count is not needed.
		// Note: this logic was added when field tokens were introduced into
		// the GET template.
		if ($this->keyValue > 0){
			$this->count = 1;
			$summary['count'] = 1;
			return $summary;
		}
		
		// Use the "COUNT" template for the SQL command.
		$sql = $this->methodSQL['COUNT'];

		// Substitute column[] field values into the SQL string.
		$sql = $this->buildSQLValueSubstitution($sql);

		debug_msg("SQL command: ${sql}", true, "executeSQLGETCount()") ;
		
		// CHANGE:	2024-07-02
		// Until now, COUNT requests would build a SELECT command.  
		// Today I have introduced a CALL command to execute a stored procedure.
		if (stripos($sql, "CALL ")!== false) $sql = $this->buildSQLCall($sql); 
		else {

			// Recapture the where clause 
			$whereClause = $this->buildWhere();

			// Replace the tokens in the query.
			$sql = str_replace("%resource%", $this->resource, $sql) ;
			$sql = str_replace("%where%", $whereClause, $sql) ;
			$sql = str_replace("%keyField%", $this->keyField, $sql);
		}

		// Execute the query.
		$result = odbc_exec($dbid,$sql) ;

		if (!$result) {return $this->postSQLError($dbid, $sql);}
		
		// The count should be the content of the first (and only) field.
		$this->count = odbc_result($result, 1);
		debug_msg("Returning Count:" . strval($this->count), true);
		
		$summary['count'] 	= $this->count;
		
		return $summary ;
	} 



	/*
	 *	Function executeSQLGETRecords executes the actual SQL SELECT query and returns the 
	 *	results in an array.
	 */
	public function executeSQLGETRecords($dbid, $sql)  {
	global $debug;

		// Set the limit to a default.
		$limitClause = "limit " . strval($this->perPage);

		// If limit is explicity set...
		if ($this->limit > 0) {
			debug_msg("Limit explicitly set to: " . strval($this->limit), true);
			$limitClause = "limit " . strval($this->limit);
		}
		else {debug_msg("No limit set.  Using default:" . strval($this->perPage), true);}


		// A page request TRUMPS a limit setting.
		if ($this->pageNo > 1) {
			$pageNo = $this->pageNo;
			$perPage = $this->perPage;
			$startRow = ($pageNo - 1) * $perPage;
			$limitClause = "limit {$perPage} offset {$startRow}";
		}

		// Replace the limit token with the clause.
		debug_msg("Limit Clause:" . $limitClause, true);
		$sql = str_replace("%limit%", $limitClause, $sql) ;

		// Execute the query.
		$result = odbc_exec($dbid,$sql) ;
		if (!$result) {return $this->postSQLError($dbid, $sql);}

		// Note that only a SQL GET will return a result...
		$rowNum = 0;
		$rowCount = odbc_num_rows($result);
		debug_msg("Row count after executing SQL command:" . strval($rowCount));

		$resultArray = array();  // To hold the results for the request;
		if ($rowCount > 0) {

			// Gather each row as an array into the result array.
			while($row = odbc_fetch_array($result)){
				$resultArray[$rowNum] = $row;
				$rowNum++;
			}
		}
		return $resultArray ;
	} 


	private function findBySecondaryKey(){

		// It's not enough to know that the secondary key fields are available.  We need
		// to know whether there is an existing entry for the secondary key values.

		$result = array();
		$dbid =	$this->dbOpen();
		$result = $this->executeSQLGETCount($dbid);
		$this->dbClose($dbid);

		// Change 12/20/2022: This procedure originally returned a value of True or False.
		// It has been changed to return the number of records found based on the secondary
		// key.  This is because duplicate records may be allowed in the resource.  This change
		// mans that ALL records that satisfy the secondary key condition should be updated.

		$recordCount = $result['count'] ;
		$message = "FindBySecondaryKey:  The number of records found was: {$recordCount}";

		if ($recordCount > 0) { debug_msg($message, false); }
		else {$this->postError($message); }
		return $recordCount;
	}
 

	/* 
	 *	foundKeyValue(input: array) - This function simply checks for the existence of
	 *				a key value in the submitted input array.  If a key value is found,
	 *				it is saved to the keyValue property for later processing.
	 */
	private function foundKeyValue($input){
		
		if (is_array($input)) {
			if (isset($input[$this->keyField])) {
				if ($input[$this->keyField] <> 0){
					$this->keyValue = $input[$this->keyField];
					return true;
				}
			}
		}
		return ($this->keyValue <> 0); 

	}

 	/*
	 * 	Returns a subset of the result set based on the page requested.
	 */
/*
	// This won't work if I am to select records based on the requested page.

	private function getPageRows($result){
			
		$startRow = ($this->pageNo - 1) * $this->perPage;
		$pageRows = array_slice($this->resultSet, $startRow, $this->perPage);
		return $pageRows;
	}

 */
 
 	/*
	 * 	Loads the secondary key into the column property to build a WHERE clause.
	 */
 
	private function getSecondaryKey($input){
	
		// If a primary key is available, then don't bother.
		if ($this->foundKeyValue($input)) {return true;}

		debug_msg("Building a Secondary Key WHERE clause.", true, "GetSecondaryKey");

	
		// Make sure a secondary key has been specified.
		if (empty($this->secondaryKey)) {return false;}	 // No secondary key is NOT an error.
		
		// Make sure we have parsed data.
		if (!is_array($input)){
			$message = "getSecondaryKey: Submitted data was not an array.";
			return $this->postError($message);
		}
		
		// Move the secondary key fields to an array for processing.
		$keyFields = explode(",", $this->secondaryKey);
		
		// Search for each secondary field in the input array.
		foreach ($keyFields as $key) {
			$fieldName = trim($key) ;		// There may be leading spaces.
			
			// If a key field is missing, then we don't have a secondary key.
			if (!isset($input[$fieldName])) {
				$message = "getSecondaryKey: Missing secondary key field: {$fieldName}" ;
				return $this->postError($message);
			}

			// Placing the secondary key values into the column property is what will
			// actually cause the WHERE clause to be generated for the secondary key.
			$this->column[$fieldName] = $input[$fieldName] ;
		}
		
		return true;
	}


	/*
	 *	isNumberNotString - Zip codes with leading zeros are prime examples of numbers that
	 *			should be interpreted as a string.  The is_numeric() function is not 
	 *			sufficient.
	 */
	private function isNumberNotString($value){
		if (!is_numeric($value)){return false;}
		$valStr = strval($value);
		if (substr($valStr, 0, 1)=="0" AND strlen($valStr) >= 5) {return false;}
		return true;	
	}


	private function listSupportedMethods() {
		
		// Returns a list of the request methods supported by scanning methodSQL.
		$list = "";
		foreach ($this->methodSQL AS $method => $sql){
			
			// Don't include methods that have no SQL template.
			if (strlen($sql) > 0){
				if (strlen($list) > 0){$list .= ", ";}
				$list .= $method;
			}
		}		
		return $list;
	}


	/*
	 *	parseData - This is a recursive routine that accepts the posted data as an array.  Multiple
	 *			records will be represented as an array of arrays.  If an element of the 
	 *			submitted array is also an array, then this procedure calls itself to further 
	 *			process the next layer of data.
	 *
	 *			As each row of data is processed, a SQL command will be generated that should
	 *			affect the update of the database--based on the data submitted.
	 */
/*  	private function _parseData($input, $level=0) {

		debug_msg("Level: " . strval($level), false, "ParseData");

		// Don't allow recursion to go too deep...
		if ($level > 2){
			$message = "Parse Data:  Nesting is too deep.";
			return $this->postError($message);
		}

		// Make sure we have an array.
		if (!is_array($input)) 	{return false;}	// This should terminate a transaction.
		if (empty($input)) 		{return false;}

		// If the first element is also an array, then recurse on each element in the array.
		try {
			$subArray = (is_array($input[0]));
		} 
		catch (Exception $e) {
			debug_msg('Parse Data exception: ' . $e->getMessage());
			$subArray = false;
		}
		
		if ($subArray){
			debug_msg("Level " . strval($level) . ":  We have a sub-array!", false, "Parse Data");

			// Bump up the level and parse the sub-array(s).
			$level++;
			foreach ($input as $key=>$value) {
				
				if (!$this->parseData($value, $level)){
					$message = "Parse Data Error at level " . 
						strval($level) . ", element# " . strval($key);
					return $this->postError($message);
				}
			}
			
			// CHANGE 09/30/2019 -- If all records have been processed, 
			//		at this level, simply return.  There's 
			//		nothing else to do.  BUT...failing to leave at this
			//		point results in an erroneous SQL statement from
			//		buildSQL() below.
			return true;
		}

		// Otherwise, parse the data and generate the SQL to update the database.

		return $this->buildSQL($input);
	} */
	
	
	/*
	 *	parseData - This is a recursive routine that accepts the posted data as an array.  
	 *			Actually, data is generally submitted as an array of arrays!
	 *			This simple realization allowed me to GREATLY simplify this routine from it's 
	 *			previous implementation.
	 *			
	 *			The basic test is, if both $input and $input[0] are arrays, then recurse and 
	 *			process the next level.  If not, build the SQL command from the data in the array.
	 *
	 *			I've also forgone some of the error checking.  buildSQL() will do its own error
	 *			reporting.
	 *			
	 *			Looks easy now.  ;-)
	 */
	private function parseData($input, $level=0) {

		debug_msg("Level: " . strval($level), false, "ParseData");

		if (!is_array($input)){
			$message = "Parse Data: Submitted data was not an array.";
			return $this->postError($message);
		}

		// Don't allow recursion to go too deep...
		if ($level > 2){
			$message = "Parse Data:  Nesting is too deep.";
			return $this->postError($message);
		}

		// Data is generally submitted as an array of records.
		$level++;
		foreach ($input as $key => $value) {

			// If the element is also an array, then recurse on each element in the array.
			if (is_array($value)) {	// Then this is an array of records.
				debug_msg("Input[{$key}] is an array.") ;
				if (!$this->parseData($value, $level)) { return false; }
			}
			else {  // This array contains record data.
				debug_msg("Input[{$key}] is NOT an array.  Attempting to construct SQL.") ;
				return $this->buildSQL($input);	
			}
		}
	}


	/*
		parseRequest() is the primary function for this class.  It breaks down the HTTP
		request into its component parts and processes each piece.  The expected result
		is a list of SQL commands stored to the SQL property for later execution of the 
		entire set.
	 */
	public function parseRequest(){

		ini_set("allow_url_fopen", false);
		
		//	Capture the list of methods supported.
		$allowHeader = "Access-Control-Allow-Methods: " . $this->listSupportedMethods();
		
		// Check for a preflight request.
		if ($this->preflight($allowHeader)) {return true;}

		// Capture the template SQL command based on the request method.	
		$sql = $this->methodSQL[$this->method];

		// If no SQL command was captured, then the method is not supported.
		if ((!$sql) or ($sql=="")) {
			$message = "Method is not supported:  " . $this->method;
			$this->error = $message;
			header($allowHeader);
			header('X-PHP-Response-Code: 405', true, 405);
			echo $message;
			return false;
		}

		// Capture the resource and instance from the URL...
		if (!$this->parseURL()) {return false;}
		
		// Posted data should be a JSON string.
		$this->postData = file_get_contents("php://input");	// Should be JSON.

		// If data is posted for updating, convert it to SQL UPDATE commands.
		if ($this->postData > "") {
			debug_msg("Posted data: " . $this->postData, false, "ParseRequest");
			$this->processData();
			}

		// Otherwise, build a SQL SELECT comand using the query string.
		else { 
			$this->buildSQL(); 
		}

		return true;
	}


	private function parseURL() {
		
		// Retrieve the resource (generally the table) and key from the first item in the 
		//	URL path...

		// Note: If no path_info was found, then this component has been called locally
		//		rather than via an HTTP request.

		if (isset($_SERVER['PATH_INFO'])){
			debug_msg("Path: ${_SERVER['PATH_INFO']} ", false, "parseURL()");
			$path = explode('/', trim($_SERVER['PATH_INFO'],'/'));

			// Separate the path into the resource and key if available.
			if (count($path) > 0) {
				$resource = preg_replace('/[^a-z0-9_]+/i','',array_shift($path));
			}
			
			// Verify that the request is for this resource.
			if ($resource != $this->resource) {
				$message = "The wrong resource, {$resource}, was requested for this service";
				$this->error = $message;
				header('X-PHP-Response-Code: 403', true, 403);
				echo $message;
				return false;		
			}

			// The next item in the path is a key to find a particular instance in the resource.
			if (count($path) >= 1) {$this->keyValue = array_shift($path);}
		}
		return true;
	}
	
	
	/*
	 *	postError - This code became so common that I pulled it into its own procedure.
	 */
	private function postError($message) {
		$this->error = $message;
		header('X-PHP-Response-Code: 500', true, 500);
		debug_msg($message, true);
		echo $message;
		return false;
	}

	/*
	 * 	postSQLError - Like postError(), this is to avoid duplicating this logic in
	 *				multiple SQL execution routines.
	 */
	private function postSQLError($dbid, $sql){
		$message = "SQL Execution Failed: "; 
		$message .= odbc_errormsg($dbid);
		$this->dbClose($dbid); 	// Close the DB before exiting!
		return $this->postError($message);  	
	}


	/*
	 *	Check for a "preflight" request from the browser...
	 */
	private function preflight($allowHeader){

		if ($this->method == 'OPTIONS') {

			// The origin may well be this same server, but it doesn't have to be.
			// So I may want to edit the following manually.  e.g.
			// header("Access-Control-Allow-Origin: https://vm.pubassist.com");
#			$protocol = ($_SERVER['HTTPS']=="on") ? "https://" : "http://" ;
			$protocol=$_SERVER['PROTOCOL'] = 
			  isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';

			$URL = $protocol . $_SERVER['SERVER_NAME'] .
			header("Access-Control-Allow-Origin: {$URL}");

			//Example: header("Allow: GET,PUT,POST,PATCH,DELETE");
			header($allowHeader);
			header("Access-Control-Allow-Headers: X-Custom-Header");
			return true;
		}
		
		// False means this is NOT a preflight request.
		return false;
	}


	/*
	 *	processData - There may be multiple records in the posted data of the request.  This procedure
	 *			processes through them and prepares SQL commands for execution.
	 */

	private function processData(){

		debug_msg("Processing: {$this->postData}, " . gettype($this->postData), true, "processData()");
		// The posted data should be in json format.
		$json = preg_replace('/[[:cntrl:]]/', '', $this->postData);
		$input = json_decode($json, true);

		// If so, does it look like JSON?
		if (!is_array($input)) {
			$message = "Process Data Error: " . json_last_error_msg();
			return $this->postError($message);
		}

		// Did we get any data?
		if ($input <= ""){ return true;}

		// Parse the posted data to generate SQL commands for the request.
		return $this->parseData($input);

	}


	/*
	 *	Send a JSON string in the HTTP response.  
	 *	This is the typical response for a GET request.
	 */
	public function respondWithJSON(){

		// Parse the HTTP request.
		if ($this->parseRequest()){

			// Execute the resulting SQL command.
			$result = $this->executeSQL();
			debug_msg("Result Array?: " . (is_array($result) ? "true" : "false"), false, "respondWithJSON()");

			// Results should only be available for a GET request.
			if ($this->method == "GET" && is_array($result)){

				// How big is the result?
				$resultLen = strval(count($result));
				debug_msg("Result Length: {$resultLen}");

				// Encode the results to JSON to respond to the client.
				$json = json_encode($result);

				// Respond with the results.
				header('Content-Type: application/json');
				echo $json;
			}
		}
	}

	/*
	 *	Report the number of SQL statements that were successfully executed 
	 *	as the HTTP response.
	 *	This is a general response for HTTP requests for other than the GET method.
	 */
	public function respondWithResult(){

		// Parse the HTTP request.
		if ($this->parseRequest()){

			// Execute the resulting SQL command.
			$result = $this->executeSQL();

			if ($result){
				
				echo "<h2>Request method: {$this->method}</h2>\n";
				echo "<h2>Resulting SQL Commands:</h2>\n";
				foreach ($this->sql as $sql) { echo $sql . "<br/>\n";}
				echo "<h2>" . strval(count($this->sql)) . 
					 " commands were successfully processed.</h2>\n";
				return true;
			}
		}
		return false;
	}


	/*
	 *	Function safestr escapes the submitted string to make it safe for potential SQL execution.
	 */
	private function safeStr($unsafestr){
		
		if (!is_string($unsafestr)) {return "";}
		
		if ($unsafestr===null) {
			debug_msg("SafeStr:  Returning NULL.");
			$result = 'NULL';
		}
		else {
			$result = "'" . addslashes($unsafestr) . "'";
			debug_msg("SafeStr: " . $result);
		}
		return $result;
	}

	
	/*
	 *	setSearch -- Each application will provide a list of fields to search that
	 * 				is specific to the resource.  But the process of setting search
	 *				criteria based on a search value will be the same for all 
	 *				applications.
	 */
	
	public function setSearch($fieldList="") {
	
		// A value to use in the search will be provided in the query
		// string of the request.
		if (isset($_GET['searchVal'])){$this->searchVal = urldecode($_GET['searchVal']);}

		// Don't search for an empty search value.
		if ($this->searchVal==""){return "No search value was provided";}

		// Move the search field list to an array for processing.
		$searchFields = explode( ',', $fieldList );

		if (!empty($searchFields)) {

			// Set up the search by assigning the search value to the various columns  
			// that may contain that value.  (Clearly, this is very loose search criteria.)
			foreach ($searchFields as $fieldName) {
				$this->column[$fieldName]=$this->searchVal;			
			}

			// Set other request properties for the search.
			$this->compare = "relaxed";	// Uses the "LIKE" and "OR" operators in the query.
			$this->limit = 10;			// Return the first 10 occurrances found.
		}
	}

}	// End of class.

?>
