<?php

/*	This is the report component for the PubAssist applications.  

	This component is intended to facilitate the development of a report for 
	any resource.	
	
 */

 
class report extends webObj {
	public $context = null;
	public $name = "Report";
	public $title = "";
	public $template = "report.html";
	public $resource = "";				// Holds the name of the resource to be requested.
	public $keyFieldName = "";			// Holds the key field for the resource.
	public $column = array();			// Holds the columns (field names) for the filter condition.
	public $filter = "";				// Holds the current filter setting for the query.
	public $sortBy = "";				// Holds the list of fields in the sort order.
	public $limit = 10000;				// Limited to 10 thousand records by default.
	public $restURL = "";				// Holds the URL for the REST API.
	public $resultSet = null;			// Holds the result of a search.
	public $count = 0;					// Holds the record count returned from the REST API.
	public $detailHTML = "";			// Holds the accumulated detail HTML between groups.


	public function __construct($resource="", $keyFieldName="", $title="", $description="", $action = ""){

		// Capture critical pointers to the REST API.
		$this->resource = $resource;
		$this->restURL = $this->getRESTurl($this->resource) ;	// in WebObj class.
		$this->keyFieldName = $keyFieldName;
		$this->thisURL = "{$resource}Report.php/";
		$htmlId = uniqid($resource);
		
		// Capture the title and description.
		if (isset($title)) $this->title = $title ;
		if (isset($description)) $this->description = $description ;
		if (isset($action)) $this->action = $action ;

		// Capture remaining request parameters...
		$this->getRequestParameters() ;
		
		// Add objects for the major bands of the report.
		// Assign this component as the parent object to each band.
		// Add the bands as children[] to allow their automatic rendering.
		$titleBand = new rptTitle("reportTitle", 
							"", 
							$this->title, 
							$this->description, 
							$this->action);
		$this->addChild($titleBand) ;
		
		$groupBand = new rptGroup("reportGroup");
		$this->addChild($groupBand) ;

		$summaryBand = new rptSummary("reportSummary");
		$this->addChild($summaryBand) ;
		
		
	}
	

	// Gather data from the REST API to present in the report.
	public function getData(){

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


	// 	This Render procedure is used to incorporate the data in the resultSet
	//	into the content of the report.

	public function render($record=null){
		
		$html = file_get_contents($this->template, FILE_USE_INCLUDE_PATH);

		// Capture the data for the report.
		$this->getData();
		
		// Render the title band.
		$title = $this->children['reportTitle'];
		$title->title = $this->title ;	
		$title->filter = $this->filter ;
		$title->count = $this->count ;
		$htmlTitle = $title->render() ;

		// Insert the title into the report template.
		$html = str_replace("{title}", $htmlTitle, $html);
		
 		// Process through each record in the result set.
		$group = $this->children['reportGroup'] ;
		$htmlDetail = "";
		foreach ($this->resultSet as $key => $record){
			$htmlDetail .= $group->render($record);
		}
		
		// Render the last group.
		$htmlDetail .= $group->renderPreviousGroup($record) ;

		// Insert the detail into the report template.
		$html = str_replace("{detail}", $htmlDetail, $html);

		// Insert the summary into the report template.

		$summary = $this->children['reportSummary'];
		$htmlSummary = $summary->render();
		$html = str_replace("{summary}", $htmlSummary, $html);

		return $html ;
		
	}
	
}


/*************************** Report Bands *****************************************/


/*
	This component renders a title band for the entire report.
 */

class rptTitle extends webObj {

	public $name = "reportTitle";
	public $template = "reportTitle.html";
	public $title = "The report title goes here.";
	public $description = "A useful description goes here.";
	public $user = "";
	public $filter = "";
	public $rptDate = "";
	public $count = 0;

	public function __construct(...$args) {
		
		parent::__construct(...$args) ;
		$this->user = $_SESSION['auth']['email'];	// Capture the current user.
		$this->rptDate = date("m/d/Y");				// Capture the current date.

	}
	
	
	public function render($record=null) {
		
		// If the template is blank, there should be no title page.
		if ($this->template == "") { return ""; }

		$html = parent::render();		// Standard render().
		
		// Use the email to identify the user.
		$html = str_replace("{user}", $this->user, $html);
		
		// Capture the current date.
		$html = str_replace("{date}", $this->rptDate, $html);
		
		// Capture the filter for the report.
		$html = str_replace("{filter}", $this->filter, $html);

		// Add the record count to the rendered HTML.
		$html = str_replace("{count}", $this->count, $html);
		
		return $html;	
	}

}


/*	
	A great deal of the report structure is defined by a "group".  A group is defined
	by a set of field values.  If any of these values change, then a new group is 
	launched.

	Because this procedure will not know when the group will change until a change is
	encountered in the group fields, this procedure accumulates the details of the group
	but winds up rendering the PREVIOUS group when the change is encountered.
	
	It should be noted that a group may have another group as a child component.
	
 */
 
class rptGroup extends webObj {

	public $name = "reportGroup";
	public $template = "rptGroup.html" ;
	public $detailTemplate = "rptGrpDetail.html" ;
	public $groupHeaderHtml = "" ;		// Holds the Header rendering for this group.
	public $previousGroupHtml = "" ;  	// Accumulate the group detail until a group changes.


	public function __construct(...$args) {

		// Add components as children to allow automatic rendering.
		$groupHeader = new groupHeader('groupHeader');
		$this->addChild($groupHeader);
		
		$groupDetail = new groupDetail('groupDetail');
		$this->addChild($groupDetail);
		
		$groupSummary = new groupSummary('groupSummary');
		$this->addChild($groupSummary);

	}

/*	
		The following methods are for the convenience of adding fields to a "group"
		object.  Group fields, group detail fields, and group summary fields 
		define the "group" and what should be reported.
		
		Each group will have a header band, a detail (table) band, and a summary
		band.

 */

	// 	Group fields define when a new group should be launched, and the previous group
	//	should be rendered.
	public function addGroupField($fieldName, $fieldLabel="", $fieldAttributes="" ) {
	
 		$groupHeader = $this->children['groupHeader'];
		$fieldObj = new rptField($fieldName, "", $fieldLabel, "", $fieldAttributes);
		$groupHeader->addChild($fieldObj);
	}
	
	// The Detail fields will be added to a table in the detail band.
	public function addDetailField($fieldName, $fieldLabel="", $fieldAttributes="" ) {
 		$groupDetail = $this->children['groupDetail'];
		$fieldObj = new rptTableField($fieldName, "", $fieldLabel, "", $fieldAttributes);
		$groupDetail->addChild($fieldObj);
	}

	// Summary fields accumulate summaries to be rendered to the summary band.
	public function addSummaryField($fieldName, $fieldLabel="", $fieldAttributes="" ) {
 		$groupSummary = $this->children['groupSummary'];
		$fieldObj = new rptSummaryField($fieldName, "", $fieldLabel, "", $fieldAttributes);
		$groupSummary->addChild($fieldObj); 
	}

	
	// If a group field value has changed, then we have a new group.
	public function isNewGroup($record) {
		
		$newGroup = false ;
		$firstRecord = false ;
		
		// Check all fields in the group header against the current record.
		$groupHeader = $this->children['groupHeader'];
		foreach ($groupHeader->children as $fieldName => $fieldObj) {
		
			// If a group field value has changed, then we have a new group.
			$fieldVal = $record[$fieldName] ;
			if ($fieldObj->value <> $fieldVal) { 

				$fieldObj->value = $fieldVal ;
				$newGroup = true ;
			}
		}

		if ($newGroup) {
			$headerObj = $this->children['groupHeader'] ;
			$this->groupHeaderHtml = $headerObj->render($record);
		}
		
		return $newGroup ;

	}


	// The group should accumulate the rendering for each record until the 
	//	group changes.
	public function render($record=null) {
		
		$html = "" ;	// Return nothing until the group is complete.

		//  If this is a new group, return the accumulated table rows for the 
		//	previous group.
		if ($this->isNewGroup($record)) {
			$html .= $this->renderPreviousGroup($record);			
		}

		// Render the current record content to a table row.
		$row = $this->children['groupDetail']->render($record) ;

		// Add the current record to the previous group.
		$this->previousGroupHtml .= $row ;
		// Summarize fields from the current record.
		$this->children['groupSummary']->summarize($record);

		return $html ;
	}
	

	// The group is not rendered until there is a change in the group or we
	// reach the end of the result set.
	public function renderPreviousGroup($record) {
		

		// If there is nothing in the previous group, then there is nothing to render.
		if (strlen($this->previousGroupHtml) == 0) return "" ;

		// Make sure the detail header has been rendered.  
		$detailObj = $this->children['groupDetail'] ;
		$detailObj->renderDetailHeader() ;
		$detailHtml = $detailObj->detailHeader ;

		// Add the previous group detail records.
		$detailHtml .= $this->previousGroupHtml ; 

		// Wrap the detail rows in a table.
		$html = file_get_contents($this->detailTemplate, FILE_USE_INCLUDE_PATH);
		$html = str_replace("{content}", $detailHtml, $html);
		$html = str_replace("{action}", $this->action, $html);

		// Add the group header to the BEGINNING of the rendering.
		$html = $this->groupHeaderHtml . $html ;

		// Add the group summary to the rendering...
		// If this is the first group, there is no need for rendering the summary.
		$html .= $this->children['groupSummary']->render($record);

		// Re-initialize the group header and previous group html for the new group.
		$headerObj = $this->children['groupHeader'] ;
		$this->groupHeaderHtml = $headerObj->render($record);
		$this->previousGroupHtml = "" ;

		return $html;
	}
	
}


/*
	The rptSummary component is used to render the summary band for the entire 
	report.
 */

class rptSummary extends webObj {

	public $name = "reportSummary";
	public $template = "reportSummary.html" ;
	public $parent = null;

	
}


/*************************** Group Band Subcomponents **********************************/

// The group header band renders the header field(s) for the group.

class groupHeader extends webObj {

	public $template = "rptGrpHeader.html" ;
	
	public function render($record=null) { 

		$html = parent::render($record);
		
		// Replace field tokens with the values in the record.
		foreach ($record as $fieldName=>$fieldVal) {
			$token = "{" . $fieldName . "}" ;
			$html = str_replace($token, $fieldVal, $html);
		}
		
		return $html ;

	}
	
}


// The detail band has a header and detail table rows. 
// The header row presents the field titles.
// The detail rows present the field values.
class groupDetail extends webObj {

	public $name = "groupDetail";
	public $template = "rptTableRow.html";
	public $detailHeader = "" ;			// Holds the detail (table) band header.


	// The detail header is rendered from the detail fields, assigned as children.
	public function renderDetailHeader() {
		
		// The header row is rendered only once for the report.
		if (strlen($this->detailHeader) > 0) return ;

		// Process through the detail fields to create the table header row.
		$content = "" ;
		foreach ($this->children as $fieldName => $fieldObj){
			$fieldHeader = $fieldObj->renderHeader($fieldObj);
			$content .= $fieldHeader ;
		}

		// Wrap the header in a row.
		$html = file_get_contents($this->template, FILE_USE_INCLUDE_PATH);
		$html = str_replace("{content}", $content, $html);
		$html = str_replace("{action}", $this->action, $html);
		$this->detailHeader = $html ;

	}
}



class groupSummary extends webObj {

	public $name = "groupSummary";
	public $template = "rptGrpSummary.html" ;


	public function render($record=null) { 

		$html = parent::render($record);
		
		// Replace field tokens with the values in the record.
		foreach ($record as $fieldName=>$fieldVal) {
			$token = "{" . $fieldName . "}" ;
			$html = str_replace($token, $fieldVal, $html);
		}
		
		return $html ;

	}
	
	
	// Process through the summary fields to summarize each one.
	public function summarize($record){
	
		foreach ($this->children as $childObj) {
			$childObj->summarize($record);
		}
	}
	
}



/****************************** Field Components **********************************/

// 	A report field is a general class of any field to be presented in the report.
// 	By default, both the label and the field value are rendered laterally (in the
//	same line) in the report.  A report field may have an HTML attribute 
//	associated with it.  A width is a typical example.

class rptField extends webObj {
	public $template = "rptLateralField.html";

	public function render($record=null) {
	
		$fieldName = $this->name ;

		// If the record contains the field value, then set the value property
		// for rendering.
		if (isset($record[$fieldName])) {
			$this->value = trim(strval($record[$fieldName]));
		}
		return parent::render($record);
	}	
}

//	Detail fields are presented in a table in the detail band of the report.

class rptTableField extends rptField {
	public $template="rptTableField.html";
	public $headerTemplate="rptGrpTHField.html";
	
	// renderHeader() creates a table header cell for this field object.
	public function renderHeader() {
		$html = file_get_contents($this->headerTemplate, FILE_USE_INCLUDE_PATH);
		$html = str_replace("{title}", $this->title, $html);
		$html = str_replace("{action}", $this->action, $html);
		return $html ;
	}

}


// 	A summary field is summarized in a limited few ways: SUM, MIN, MAX and possibly 
//	AVE.  These summarizations must happen as each record is processed.  These
//	summary fields are to be rendered in the summary band of the report.

class rptSummaryField extends webObj {

#	public $template = "rptLateralField.html";
	public $template = "rptSummaryRow.html";
	public $count = 0;
	public $sum = 0;
	public $max = null;
	public $min = null;
	public $whichSummary = "SUM";	// The default.

	public function render($record=null) {

		// Which summarization should be rendered?
		switch ($this->whichSummary) {
			case "COUNT":
				$this->value = $this->count ;
				break;
			case "SUM":
				$this->value = $this->sum ;
				break;
			case "MIN":
				$this->value = $this->min ;
				break;
			case "MAX":
				$this->value = $this->max ;
				break;
			case "AVE":
				$this->value = $this->sum / $this->count ;
				break;
		}

		// Reinitialize the summary properties...
		$this->count = 0;
		$this->sum = 0;
		$this->max = null;
		$this->min = null;

		// Render this summary field.
		$html = parent::render();
		
		// Wrap the summary field in a table row.
#		$html = file_get_contents($this->rowTemplate, FILE_USE_INCLUDE_PATH);
#		$html = str_replace("{content}", $content, $html);
		
		return $html;

	}


	public function summarize($record){
		
		$fieldName = $this->name;

		// Check to see that the field is in the record.
		if (!isset($record[$fieldName])) { return; }
		
		// Capture the field value to this object.
		$this->value = trim(strval($record[$fieldName]));
		
		// Count the number of records in the group.
		$this->count++;
		
		// Accumulate the sum of numeric values.
		if (is_numeric($this->value)){ $this->sum += $this->value; }
		
		// Retain the maximum value;
		if (is_null($this->max) OR $this->value > $this->max) { 
			$this->max = $this->value; 
		}
		
		// Retain the minimum value;
		if (is_null($this->min) OR $this->value < $this->min) { 
			$this->min = $this->value; 
		}

	}
}



?>