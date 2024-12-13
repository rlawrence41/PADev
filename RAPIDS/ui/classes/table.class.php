<?php

/*

	The Table class will obtain data from the REST API and render the results
	to an HTML table.
	
	The approach taken here is to use the webObj class to create each element
	of the table.  The heirarchy of table elements should look familiar:
	
	table
		tableHeader
			columnHeader(field1)
			columnHeader(field2)
				...
			columnHeader(fieldN)
		tableBody
			row1
				cell(field1)
				cell(field1)
				...
				cell(field1)
			row2
			...

	Using the standard webObj works well for this; and rendering the table 
	is done, automatically, assuming that each element is properly added to
	the list of children for its parent container... (easier said than done.)
	
								*	*	*	*
	
	Of particular note, the routines to add elements as subcomponents is all
	programmed within the table class.  It might seem that these routines
	to add children should be contained within each sub-class.  
	
	HOWEVER, ADDING CELLS TO A ROW REQUIRES UNDERSTANDING WHETHER THE 
	ASSICIATED FIELD IS DEFINED IN THE TABLE HEADER.
	
	This is manifested in the isColumn() method.  Because of the need to 
	reference this list in a sibling component, the method needs to be in the 
	parent component.  This has the added benefit of keeping the code in one 
	place while keeping all of the child components very simple.
		
								*	*	*	*

	Another issue worth noting in a structure as complex as this:  
	
	Since the child classes are so simple, there is a tendency to overlook 
	the need for unique names for each. For example, during development, 
	there seemed to be no real significance and no unique tags for table rows. 
	So, I neglected to assign any to the webObj for the row.  
	
	However, the webObj->addChild() uses the name to add the child to the 
	list of children.  Without a unique name, the webObj retains it's default
	name, "webObj".  Thus, each row was assigned to the table body class as
	children['webObj'].  This name association is normally a very handy 
	feature, but the result here was that ONLY the last record was rendered!
	
	SO, IT IS VERY IMPORTANT TO GIVE EACH NEW CHILD IN A LIST A UNIQUE NAME!
	
 */


class table extends webObj{
	public $context = null;
	public $template = "table.html";
	public $keyFieldName = "";		// This is the primary key field for the resource.
	public $resource = "";			// Holds the resource associated with the table.
	public $restURL = "";			// Holds the address for the REST API call for the resource.
	public $thisURL = "";			// Holds the address for the page that renders the table.
	public $resultSet = array();	// Holds the json data in an array.
	public $count = 0;				// Holds the record count returned from the REST API.

	// The table may contain a number of action buttons or icons to trigger 
	// specific actions on the record in the row.
	public $applyCheckBox = false;	// Adds a checkbox to each row to allow the instance to be "applied".
	public $applyAction = "";		// Action taken when the instance is applied.
	public $applyColumn = "";		// This is the field name that determines whether the instance is applied.
	public $editButton = true;		// Include edit buttons for each row by default.
	public $editAction = 'onclick="getInstance({keyValue})"';
	public $deleteButton = true;
	public $deleteAction = 'onclick="deleteInstance({keyValue})"';
	public $selectButton = false;	// Include an select button to select the record.
	public $selectAction = 'onclick="reportTransaction({keyValue})"';
	
	public function __construct($resource, $keyFieldName, $title="", $description=""){
		
		$this->resource = $resource;
		$this->keyFieldName = $keyFieldName;
		$this->restURL = $this->getRESTurl($resource);
		$this->thisURL = "{$resource}Table.php/";
		$htmlId = uniqid($resource);
		$name = $resource . "Table";
		if (empty($title)){$title = ucfirst($resource) . " Table";}
		if (empty($description)){$description = "List of " . ucfirst($resource) ."s" ;}
		
		// Check whether the selection options should be set for a wizard step.
		$this->checkSelectOptions();
		
		//e.g. ("contactTable", "contact1234567", "Contact Table", "List of Contacts")
		parent::__construct($name, $htmlId, $title, $description);

		// Add the table header.
		$header = new tableHeader("header");
		$this->addChild($header);
		
		// If the apply option is set, add the apply checkbox column header.
		if ($this->applyCheckBox) {
			$header = new columnHeader("apply", "applyHdr", "Apply?", "Check each instance you want to apply." );
			$this->children['header']->addChild($header);
		}
	}

	// Add action buttons (icons) to an action cell if warranted.

	public function addActionButtons($row, $record){

		$actionCell = new tableActionCell("Action");

		// Are there any action buttons to add?
		if ($this->editButton) {
			$button = new editButton("Edit?");
			$button->keyValue = $record[$this->keyFieldName];
			$button->action = $this->editAction;
			$actionCell->addChild($button);
		}
		if ($this->deleteButton) {
			$button = new deleteButton("Delete?");
			$button->keyValue = $record[$this->keyFieldName];
			$button->action = $this->deleteAction;
			$actionCell->addChild($button);
		}
		if ($this->selectButton) {
			$button = new selectButton("Select?");
			$button->keyValue = $record[$this->keyFieldName];
			$button->action = $this->selectAction;
			$actionCell->addChild($button);
		}

		// Did we add any actions?
		if (count($actionCell->children) > 0) $row->addChild($actionCell); 
		
	}


	// In the orderReceipt and itemReceipt applications, there is a need to select
	// candidates to apply funds to.  This is a separate control for a unique 
	// situation; but it presents a checkbox in column 1 of the table.
	
	public function addApplyCell($row, $record) {

		if ($this->applyCheckBox) {
			$cell = new applyCheckBox("Apply?");
			$cell->keyValue = $record[$this->keyFieldName];
			$cell->htmlId = "applycbx" . strval($row->index);
			$cell->value = $record[$this->applyColumn];
			$cell->action = $this->applyAction;

			$row->addChild($cell);
		}
	}


	// 	AddCells(row: obj, record:array) accepts a row object and an array 
	//	representing a row of data from the result set.  It adds cell objects
	//	to the row.
	public function addCells($row, $record=array()){
		
		// If the apply option is set, add the apply check box column.
		$this->addApplyCell($row, $record) ;

		foreach ($record as $fieldName => $fieldValue) {

			// If the field name is found in the column set, then add the
			// cell to the row.
			if ($this->isColumn($fieldName)){
				$cellId = $fieldName . strval($row->index);
				$cell = new tableCell($fieldName,$cellId, $fieldName);
				$cell->value = $fieldValue;
				$row->addChild($cell);
			}
		}
		
		//	Add the action buttons for each row.
#		$this->addEditButtons($row, $record);
		$this->addActionButtons($row, $record);
	}


	// 	addColumn() adds a column definition to the header for the table.
	// 	This procedure is called from the resource-specific table procedure.
	//	e.g. contactTable.php
	// 	THAT'S WHAT MAKES EACH TABLE UNIQUE TO THE RESOURCE BEING PRESENTED.

	// Here's an example:
	// 	$table->addColumn("contact_id","Contact Id", "A short, memorable name for the account");

	public function addColumn($fieldName, $title="", $description="") {
		$column = new columnHeader($fieldName, "", $title, $description);
		$this->children['header']->addChild($column);
	}


	// 	This procedure builds the table body from the results of the
	//	REST request.
	public function addContent(){
		
		$body = new tableBody("body");
		
		foreach ($this->resultSet as $index => $record) {

			// Give each row a unique name.
			$this->addRow($index, $body, $record);
		}
		$this->addChild($body);
	}


	// The container for the application MUST share its context with 
	// this component.
	public function addContext($contextObj){
		$this->context = $contextObj;
	}

	//	Add a row to the table body.
	public function addRow($index, $body, $record=array()){
		// Note that each row should be given a unique name!
		// Otherwise, you end up with only a SINGLE row in the table.
		$row = new tableRow($index);
		$this->addCells($row, $record);
		$body->addChild($row);
	}


/* 

The advent of the transaction wizard requires that the table class optionally
present the selection icon.  A specific action will be assigned to that icon.
In the case of the wizard, the table will not be rendered until the wizard step
is activated.  The options for the select icon must therefore be passed through to 
the table when it is instanced.  The best option seems to be the $_SESSION 
object.
	
 */
 
	// Capture the table properties from the $_SESSION object.
	public function checkSelectOptions(){

		if (isset($_SESSION['tableProperties'])) {
			$tableProperties = $_SESSION['tableProperties'];

			if ($tableProperties['resource'] == $this->resource){
				$this->selectAction = $tableProperties["selectAction"];
				$this->selectButton = true;
			}
		}
	}


	// getData() will request data from the REST API to present in the table.
	public function getData(){

		// Capture the query string that made this request.
		$queryStr = $this->getQueryStr() ;
		$url = $this->restURL . $queryStr ;
		debug_msg("REST API Call: " . $url, true, "Table->GetData()");
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
		
		// Check for an error.
		if (json_last_error() === JSON_ERROR_NONE) {
			// JSON is valid

			// Separate the summary and record set.		
			$summary = $result[0];
			$this->count = $summary['count'];

			$this->resultSet = $result[1];
		}
		else {$this->errorMessage = "No data was returned.  " . 
				json_last_error_msg() . ":  " .	$json; }
	}


	// Build a new query string using only the column[] parameters submitted to this request.
	private function getQueryStr() {
		
		$queryStr = trim($_SERVER['QUERY_STRING']);
		$parameters = explode("&", $queryStr) ;
		$filterStr = "";
		$newQueryStr = "";
		foreach ($parameters as $parameter) {

			// If a column[] entry is found, add it to the filter string.
			if (stripos($parameter, "column") !== false){
				if (strlen($filterStr) > 0) $filterStr .= "&";
				$filterStr .= $parameter;
			}
			else {
				if (strlen($newQueryStr) > 0) $newQueryStr .= "&";
				$newQueryStr .= $parameter;
			}
		}

		// Put them back together.
		if (strlen($filterStr) > 0) {
			if (strlen($newQueryStr) > 0){
				$newQueryStr .= "&";
				$newQueryStr .= $filterStr;
			}
			else {
				$newQueryStr=$filterStr;
			}
		}

		$newQueryStr = "?" . $newQueryStr;
		return $newQueryStr;
	}


	// THIS IS WHY addRow() AND addCells() ARE METHODS IN THIS CLASS!
	// Cells are only added if the header for that field has been defined.
	// So, this function needs to refer to the children of the table header.
	private function isColumn($fieldName){
		// If the table has a column header with the same name as the submitted
		// field, then return TRUE.
		return (isset($this->children['header']->children[$fieldName]));
	}
	
	
	public function render($record=null){
	
		// Add an action column headers before rendering the table.
		if ($this->editButton OR $this->deleteButton OR $this->selectButton) {
			$header = new columnHeader("action", "actionHdr", "Action", "Click an icon to take that action on the row." );
			$this->children['header']->addChild($header);
		}

 		// Get the data from REST.
		$this->getData();

		// Add it to the table.
		$this->addContent();

		// Now, complete the normal render process.
		$html = parent::render();
		
		// Add the record count to the rendered HTML.
		$html = str_replace("{count}", $this->count, $html);

		return $html;
	}

}



class tableBody extends webObj{
	public $template = "tableBody.html";
}

class tableHeader extends webObj{
	public $template = "tableHeader.html";
}

class columnHeader extends webObj{
	public $template = "columnHeader.html";
}

class tableRow extends webObj{
	public $index = 0;
	public $template = "tableRow.html";	
	
	public function __construct($index){
		$this->index = $index;
		$name = "row" . strval($index);
		$htmlId = $name;
		parent::__construct($name, $htmlId, "", "");
	}
}

class tableCell extends webObj{
	public $template = "tableCell.html";
}


class tableActionCell extends webObj{
	public $template = "tableActionCell.html";
}


class applyCheckBox extends webObj{
	public $template = "tableApplyCheckbox.html";
	public $keyValue = 0 ;		// Generally required for most actions on a table row.
	public $parameters = "" ;	// May be used by specific applications.
	public $applyColumn = "";	// Field name that indicates the instance has been applied.
	
	public function render($record=null) {
		// Add the key value and/or parameters for the record to the action 
		// buttons.

		$html = parent::render();
		// Render the key value.
		$keyValStr = strval($this->keyValue);
		$html = str_replace("{keyValue}", $keyValStr, $html);
		// Render the parameters list.
		$html = str_replace("{parameters}", $this->parameters, $html);
		// If the value is set, then check the box.
		if (isset($this->value)) $checked = "checked";
		else $checked = "";
		$html = str_replace("{checked}", $checked, $html);
		
		return $html;
	}
}


class deleteButton extends webObj{
	public $template = "tableDeleteButton.html";
	public $keyValue = 0 ;		// Generally required for most edits on a table row.
	public $parameters = "" ;	// May be used by specific applications.
	
	public function render($record=null) {
		// Add the key value for the record to the delete button.
		$html = parent::render();
		// Render the key value.
		$keyValStr = strval($this->keyValue);
		$html = str_replace("{keyValue}", $keyValStr, $html);
		return $html;
	}
}


class editButton extends webObj{
	public $template = "tableEditButton.html";
	public $keyValue = 0 ;		// Generally required for most edits on a table row.
	public $parameters = "" ;	// May be used by specific applications.
	
	public function render($record=null) {
		// Add the key value and/or parameters for the record to the edit 
		// buttons.
		$html = parent::render();
		// Render the key value.
		$keyValStr = strval($this->keyValue);
		$html = str_replace("{keyValue}", $keyValStr, $html);
		// Render the parameters list.
		$html = str_replace("{parameters}", $this->parameters, $html);
		return $html;
	}
}

class selectButton extends webObj{
	public $template = "tableSelectButton.html";
	public $keyValue = 0 ;		// Generally required for most actions on a table row.
	public $parameters = "" ;	// May be used by specific applications.
	
	public function render($record=null) {
		// Add the key value and/or parameters for the record to the action 
		// buttons.

		$html = parent::render();
		// Render the key value.
		$keyValStr = strval($this->keyValue);
		$html = str_replace("{keyValue}", $keyValStr, $html);
		// Render the parameters list.
		$html = str_replace("{parameters}", $this->parameters, $html);
		return $html;
	}
}

