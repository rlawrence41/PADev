<?php
/*
 *	csvExport -- This class will obtain data from the REST API and render the results
 *				to CSV file on the client's machine.
 *
 *				This component has been developed in a similar fashion to other UI
 *				components, but differs in several ways:
 *
 *				The render() method for this component causes a CSV file to be 
 *				rendered and downloaded.  For that reason, this component should 
 *				NOT BE INCLUDED AS A CHILD OF ANY OTHER COMPONENT ON THE PAGE.
 *
 *				Unlike other UI components, this procedure does NOT have a template.
 *				This procedure essentially converts a JSON response from REST into
 *				CSV content.  
 *
 *				That content will be transmitted directly to the client via an HTTP
 *				response directed to a download file.
 *
 *				This component requires the specific URL of the REST 
 *					API to call. (e.g. "/common/rest/contact.php")
 */

class CSVexport extends webObj {

	public $resource = "";				// Holds the name of the resource to be requested.
	public $keyField = "";				// Holds the key field for the resource.
	public $filter = "";				// Holds the current filter setting for the query.
	public $column = array();			// Holds the field names and values used in a filter.
	public $sortBy = "";				// Holds the list of fields in the sort order.
	public $limit = 10000;				// Limited to 10 thousand records by default.
	public $restURL = "";				// Holds the URL for the REST API.
	public $resultSet = null;			// Holds the result of a search.


	public function __construct(){
		
		if (isset($_GET['resource'])) 	$this->resource = $_GET['resource'];
#		if (isset($_GET['filter'])) 	$this->filter = $_GET['filter'];

		// The filter condition will appear in the column[] array in the query
		// string.
		if (isset($_GET['column'])){$this->column = $_GET['column'];}

		if (isset($_GET['sortby'])) 	$this->sortBy = $_GET['sortby'];
#		debug_msg("Sort By:  {$this->sortBy}", true) ;
		

		// This procedure can not rely on the $GLOBALS['RESTroot'] because it is called from
		// different contexts.
		$this->restURL = $this->getRESTurl($this->resource) ;

	}


	// Gather data from the REST API to present in the table.
	public function getData(){

		debug_msg("CSV Export REST Root: " . $GLOBALS['RESTroot'], true, "Table->GetData()");

		// Attach a default query string to the request.
		$queryString = "?limit=" . strval($this->limit);	// Default should always be available.
		if (isset($_GET['column'])) {
			
			$queryString .= "&" . $this->filter ;

			// Add the filter condition from the columns.
			$filterStr = "";
			foreach ($this->column as $fieldName=>$value) {
				if ($filterStr > "") $filterStr .= '&' ;
				$filterStr .= "column[{$fieldName}]=" ;
				$filterStr .= urlencode($value);
				debug_msg("Filter String: " . $filterStr, true) ;
			}
			if ($filterStr > "") {
				$this->filter = $filterStr ;
				$queryString .= "&" . $filterStr ;
			}
		}
		
#		debug_msg("Sort By:  {$this->sortBy}", true) ;
		// Add the sortBy field list.
		if ($this->sortBy > "") {
			$queryString .= "&sortBy=" . $this->sortBy ;
			debug_msg("Sort By: " . $this->sortBy, true) ;
		}
		else debug_msg("No Sort By clause! ") ;

		$url = $this->restURL . $queryString ;
		debug_msg("CSV Export REST API Call: " . $url, true, "Table->GetData()");
		$json = postToURL($url);

		/*	A JSON Sample...
			NOTE the presence of the summary information in the first element.
		
		[{"count":"31","page":"3","perPage":10},
		[{"id":"94424","contact_id":"","company":"","last_name":"Lawrence","first_name":"Gale","po_addr":"884 Sherman Hollow Road","city":"Huntington","state_abbr":"VT","zip_code":"05462","country":"","email":"igale@accessvt.com","phone":"802-434-3595","phone2":"","web_url":"","webservice":""},
		 {"id":"88469","contact_id":"","company":"IN DEFENSE OF ANIMALS","last_name":"LAWS","first_name":"JENNIFER","po_addr":"131 CAMINO ALTO, SUITE E","city":"MILL VALLEY","state_abbr":"CA","zip_code":"94941","country":"","email":"","phone":"415-388-9641","phone2":"","web_url":"","webservice":""},
		 ...
		 ]]
		 */
		 
		// Convert the JSON data returned to an associative array.
		$result = json_decode($json, true);
		if (is_array($result)) {debug_msg("Data was successfully retrieved!", true);}
		else {
			debug_msg("Data was NOT successfully retrieved!", true);
			echo $json ;
		};
		
		// Check for an error.
		if (json_last_error() === JSON_ERROR_NONE) {
			// JSON is valid

			// Separate the summary and record set.		
			$summary = $result[0];
			$this->count = $summary['count'];
			debug_msg("Records retrieved: " . strval($this->count), true);

			$this->resultSet = $result[1];
		}
		else {$this->errorMessage = "No data was returned.  " . 
				json_last_error_msg() . ":  " .	$json; 
				debug_msg($this->errorMessage, true) ;
			}
	}


	// Compose a file name from the current resource and context.
	private function getFileName(){
		$fileName = $this->resource ;
		if (!empty($this->filter)) {$fileName .= str_replace("column", "", $this->filter);}
		if (!empty($this->sortBy)) {$fileName .= "_BY_" . str_replace(",", "&", $this->sortBy);}
		$fileName = str_replace(" ", "_", trim($fileName)) . ".csv" ;
//		$fileName = urlencode($fileName) ;
		debug_msg("File Name for CSV Download: " . $fileName, true);
		return urlencode($fileName) ;
	}


	public function render($record=null){
	
 		// Get the data from REST.
		$this->getData();
		if (is_null($this->resultSet)) return ;
		
		// Compose a meaningful file name.
		$fileName = $this->getFileName();
		
		// Initiate the response with the header.
		header('Content-Type: application/csv');
#		header('Content-Disposition: attachment; filename="sample.csv"');
		$headerStr = "Content-Disposition: attachment; filename=\"{$fileName}\"" ;
		header($headerStr);
		header('Pragma: no-cache');
		
		// sendContent() will be used to send csv content to the output buffer.
#		readfile("/var/www/ccgopvt/downloads/sample.csv");
				
		// Continue to respond with the CSV content.
		$this->sendContent($this->resultSet);
		
	}


	// 	This procedure sends the CSV content from the results of the REST request.
	private function sendContent($result){
#		global $eol;
		
		$glue = ", " ;
		$columns = array_keys($result[0]);
		$csvRecord = implode($glue, $columns); 
		echo $csvRecord . PHP_EOL;

		// Process through all records in the result set.
		foreach ($result as $key => $record) {
			
			$csvRecord = "";

			// For each record, check the values to see how they should be rendered
			// in the output.
			foreach ($record as $column => $value) {
#				echo "Column: {$column}, Value: {$value}" . $eol;
				$record[$column] = checkValue($column, $value);
			}
			$csvRecord = implode($glue, $record) ; 
			echo $csvRecord . PHP_EOL ;
		}
	}

}

?>