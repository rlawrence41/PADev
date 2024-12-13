<?php

/*	This is the label component for the PubAssist applications.  

	A "label" in this context refers to a simple report of data to multiple labels
	on a page.  Each instance in the result set will be printed to a new label.
	
 */

 
class labelPage extends webObj {

	public $context = null;
	public $name = "LabelPage";
	public $title = "";
	public $template = "labelPage.html"; // The page-level template for the labels.
	public $labelTemplate = "";			// Holds the HTML template for the individual labels.
	public $resource = "";				// Holds the name of the resource to be requested.
	public $keyFieldName = "";			// Holds the key field for the resource.
	public $column = array();			// Holds the columns (field names) for the filter condition.
	public $filter = "";				// Holds the current filter setting for the query.
	public $sortBy = "";				// Holds the list of fields in the sort order.
	public $limit = 10000;				// Limited to 10 thousand records by default.
	public $restURL = "";				// Holds the URL for the REST API.
	public $resultSet = null;			// Holds the result of a search.
	public $count = 0;					// Holds the record count returned from the REST API.
	public $labelsPerPage = 10;			// Holds the number of label panels on a page.
	public $labelStart = 1;				// Holds the starting label number for the first page.


	public function __construct($resource="", $keyFieldName="", $title="", $description="", $action = ""){

		// Capture critical pointers to the REST API.
		$this->resource = $resource;
		$this->restURL = $this->getRESTurl($this->resource) ;	// in WebObj class.
		$this->keyFieldName = $keyFieldName;
		$this->thisURL = "{$resource}Report.php/";
		$htmlId = uniqid($resource);
		
		// Capture the title, description, and action.
		if (isset($title)) $this->title = $title ;
		if (isset($description)) $this->description = $description ;
		if (isset($action)) $this->action = $action ;

		// Capture remaining request parameters...
		$this->getRequestParameters() ;
		
	}


	// Gather data from the REST API to present in the report.
	public function getData(){

		debug_msg("Retrieving data for label.", true, "{$this->name}::getData()") ;

		// Attach a default query string to the request.
		$queryString = "?limit=" . strval($this->limit);	// Default should always be available.

 		// If the filter is already set, go with it.
		if ($this->filter > "") {
			$queryString .= "&" . $this->filter ;
			debug_msg("Filter String: " . $this->filter) ;
		}
		else {
			// Add the filter condition from the columns.
			$filterStr = "";
			foreach ($this->column as $fieldName=>$value) {
				if ($filterStr > "") $filterStr .= '&' ;
				$filterStr .= "column[{$fieldName}]=" ;
				$filterStr .= urlencode($value);
			}

			debug_msg("Filter String: " . $filterStr) ;
			if ($filterStr > "") {
				$this->filter = $filterStr ;
				$queryString .= "&" . $filterStr ;
			}
		}
		
		// Add the sortBy field list.
		if ($this->sortBy > "") {
			$queryString .= "&sortBy=" . $this->sortBy ;
		}
		debug_msg("Sort By:  " . $this->sortBy) ;


		$url = $this->restURL . $queryString ;
		debug_msg("REST API Call: " . $url);
		$json = postToURL($url);

		/*	A JSON Sample...
			NOTE the presence of the summary information in the first element.
		
		[{"count":"31","page":"3","perPage":10},
		[{"contact_no":"94424","contact_id":"","company":"","last_name":"Lawrence","first_name":"Gale","po_addr":"884 Sherman Hollow Road","city":"Huntington","state_abbr":"VT","zip_code":"05462","country":"","email":"igale@accessvt.com","phone":"802-434-3595","phone2":"","web_url":"","webservice":""},
		 {"contact_no":"88469","contact_id":"","company":"IN DEFENSE OF ANIMALS","last_name":"LAWS","first_name":"JENNIFER","po_addr":"131 CAMINO ALTO, SUITE E","city":"MILL VALLEY","state_abbr":"CA","zip_code":"94941","country":"","email":"","phone":"415-388-9641","phone2":"","web_url":"","webservice":""},
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


	// Capture parameters from the HTTP Request string.
	public function getRequestParameters() {
		
		// If the filter is already set, it may be submitted in the request.
		if (isset($_GET['filter'])) $this->filter = $_GET['filter'];
		
		else {

			// The filter condition will appear in the column[] array in the query
			// string.
			if (isset($_GET['column'])){$this->column = $_GET['column'];}

			// Derive the filter condition from the columns.
			$filterStr = "";
			foreach ($this->column as $fieldName=>$value) {
				if ($filterStr > "") $filterStr .= '&' ;
				$filterStr .= "column[{$fieldName}]=" ;
				$filterStr .= urlencode($value);
			}

			debug_msg("Filter String: " . $filterStr) ;
			if ($filterStr > "") {
				$this->filter = $filterStr ;
			}
		}

		// A sortby expression may also be set.
		if (isset($_GET['sortby'])) 	$this->sortBy = $_GET['sortby'];
		debug_msg("Sort By:  {$this->sortBy}") ;
		
	}
	

	// 	Render each instance in the resultSet to a new label panel.
	public function render($record=null){
		
		// Capture the data for the report.
		$this->getData();

		// Capture the HTML for the page break for the label.
		$pageBreak = file_get_contents("pageBreak.html", FILE_USE_INCLUDE_PATH);


 		// Process through each record in the result set to render the label.
		$contentHtml = "";
		$index = $this->labelStart - 1;	// Set the label to start on.
		foreach ($this->resultSet as $key => $record){

			$index++;	// Each record is one label.
			
			// Capture a fresh copy of the label HTML.
			$labelHtml = file_get_contents($this->labelTemplate, FILE_USE_INCLUDE_PATH);

			// Replace field tokens with the values in the record.
			foreach ($record as $fieldName=>$fieldVal) {
				$token = "{" . $fieldName . "}" ;
				$labelHtml = str_replace($token, $fieldVal, $labelHtml);
			}
			$contentHtml .= $labelHtml;
			
			// Page break when the labels per page  or the last record is encountered.
			if ((($index) % $this->labelsPerPage == 0) || ($index==$this->count)) {
				$contentHtml .= $pageBreak;
			}
		}

		// Insert the content into the page template.
		$html = file_get_contents($this->template, FILE_USE_INCLUDE_PATH);
		$html = str_replace("{content}", $contentHtml, $html);

		return $html ;
	}

}
