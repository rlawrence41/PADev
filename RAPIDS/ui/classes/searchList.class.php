<?php

/*
 *	searchList -- This the User Interface component for presenting the 
 *				search list (pseudo popup) for a search field.  The
 *				content of the popup is a short list of instances that
 *				match the search value.
 *
 *				This component requires the specific URL of the REST 
 *					API to call. (e.g. "/contact/rest/contact.php")
 */

class searchList extends webObj {

	public $template = "searchList.html";	// HTML template
	public $keyFieldName = "";				// Holds the key field for the foreign resource.
	public $searchURL = "";					// Holds the URL for the resource-specific searchList.
	public $sortBy = "";					// Holds the list of fields in the sort order.
	public $context = null;					// Holds the context for the search.
	public $limit = 10;						// Returns 10 records by default.
	public $searchVal="";					// Value to search for.
	public $searchFields=array();			// Holds the list of fields to search.
	public $searchFor = "";					// Holds the query string for the REST request.
	public $resultSet = null;				// Holds the result of a search.
	public $restURL = "";					// Holds the URL for the rest API.


	public function __construct($keyFieldName, $resource){
		
		$this->keyFieldName = $keyFieldName;

debug_msg("REST root: " . $GLOBALS['RESTroot'], true, "searchList::__construct()") ;

		$this->restURL = $this->getRESTurl($resource);
		$this->searchURL = $_SERVER['PHP_SELF'];
		$this->searchVal = urlencode($_GET['searchVal']);
		parent::__construct("searchList");
	
	}

	public function addListItem($name, $htmlId, $title){

		$listItem = new searchListItem($name, $htmlId, $title);
		
		// Add the action for the search list item.  Pay attention to quotes!
		$listItem->action = "onclick='selectItem(this)'";
		$this->addChild($listItem);

	}


	/*
	 * buildList() - Builds the search list based on the search results.
	 *
	 *			This procedure now works based on adding searchListItem components
	 *			as children to this component.
	 */

	public function buildlist(){

		$keyField = $this->keyFieldName;
		
		// Make sure we have data to render a search list.
		if (empty($this->resultSet)) {
			$listItem = new searchListItem();
			$listItem->template = "searchListItemDisabled.html";
			$this->addChild($listItem);
		}
		else {
			foreach ($this->resultSet as $instance){
				// Note that the key field must be included, but separated from the other values.
				$instanceStr = "";
				$name = uniqid("noName");
				$keyVal = 0;
				foreach ($instance as $field=>$value){

					// If the key field, capture its value for the list item.
					if ($field == $keyField ){
						$keyVal = $value;				// Use the key value as an Id.
						$name = "li" . strval($value);	// Give the list item a unique name.
					}

					// If the field is in the search list, add its value to present 
					// in the list.
					if (in_array($field, $this->searchFields, true)) {

						// Accumulate field values separated by a comma.
						if ($instanceStr > "") {$instanceStr .= ", " ;}
						$instanceStr .= $value;

					}
				}

				// Add the list item as a child node.
				$this->addListItem($name, $keyVal, $instanceStr);
			}
		}
	}


	// Gather data from the REST API to present in the searchlist.
	public function getData(){

		debug_msg("Searching for: {$this->searchFor}", true, "searchList->getData()");

		// Build the query string for a search.
		$limitStr = strval($this->limit);
		$queryString = "?limit={$limitStr}&compare=relaxed";
		$queryString .= $this->searchFor;
		if (!empty($this->sortBy)) {
			// Remove whitespace from the sortBy string before adding to the 
			// query string.
			$sortBy = preg_replace('/\s/', '', $this->sortBy);
			$queryString .= "&sortBy={$sortBy}";
		}

		// Add the query string to the URL.
		$url = $this->restURL . $queryString;

		debug_msg("Full URL: {$url}", true, "searchList::getData()");

		$json = postToURL($url);
		
#		debug_msg("Response: {$json}", true);
		
		/*	A JSON Sample...
			NOTE the presence of the summary information in the first element.
		
		[{"count":"31","page":"3","perPage":10},
		[{"id":"94424","contact_id":"","company":"","last_name":"Lawrence","first_name":"Gale","po_addr":"884 Sherman Hollow Road","city":"Huntington","state_abbr":"VT","zip_code":"05462","country":"","email":"igale@accessvt.com","phone":"802-434-3595","phone2":"","web_url":"","webservice":""},
		 {"id":"88469","contact_id":"","company":"IN DEFENSE OF ANIMALS","last_name":"LAWS","first_name":"JENNIFER","po_addr":"131 CAMINO ALTO, SUITE E","city":"MILL VALLEY","state_abbr":"CA","zip_code":"94941","country":"","email":"","phone":"415-388-9641","phone2":"","web_url":"","webservice":""},
		 ...
		 ]]
		 */

		$result = json_decode($json, true);
		// Not interested in the summary info.  The data is in element [1].
		$this->resultSet = $result[1];
	}


	public function render($record=null) {

		$this->getData();		// Captures the result set.
		$this->buildList();		// Adds list items as children based on the result set.
		$html = parent::render();
		return $html;

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
		if (isset($_GET['searchVal'])){$this->searchVal = urlencode($_GET['searchVal']);}

		// Don't search for an empty search value.
		if ($this->searchVal==""){return "No search value was provided";}

		// Move the search field list to an array for processing.
		$justFields = preg_replace('/\s/', '', $fieldList);	// Remove white spaces
		$searchFields = explode( ',', $justFields );		// Create the array
		$this->searchFields = $searchFields;				// Save the array of fields.

		if (!empty($searchFields)) {

			// Set up the search by assigning the search value to the various columns  
			// that may contain that value.  (Clearly, this is very loose search criteria.)
			foreach ($searchFields as $fieldName) {
				$this->searchFor .= "&column[{$fieldName}]={$this->searchVal}";
			}
		}
	}
}


class searchListItem extends webObj {

	public $template = "searchListItem.html";
	
}