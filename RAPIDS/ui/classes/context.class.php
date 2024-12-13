<?php

/*
 *	context class -- Holds the context for a container component.
 *
 *			Context must be shared between server and client, but I'm striving
 *			to maintain a stateless environment.  The concept of "widgets" 
 *			introduces the need to isolate the context for each widget within 
 *			an application.  Assigning the context to an object seems like a 
 *			good way to make it transportable.
 */
 
 class context {

	/*
	 *	These are ONLY the INITIAL settings for the context.  
	 *	These variables will be maintained in Javascript.
	 */
	public $column = array();
	public $id = "contextId";
	public $filterStr = "";
	public $persistentFilter = "" ;
	public $keyFieldName = "";
	public $lastPage	= 100;		// Just some room for testing.
	public $limit		= 10000;
	public $pageNo		= 1;
	public $perPage		= 10;
	public $resource 	= "";
	public $restURL		= "";
	public $sortBy 		= "" ;
	public $template = "context.html";

	public function __construct($resource, $keyFieldName) {

		$this->thisURL = $_SERVER['REQUEST_URI'];  // Pointer to self.

		// The parameters are specific to a resource and application.
		$this->resource = $resource;
		$this->keyFieldName = $keyFieldName;
		$this->restURL = $this->getRESTurl($resource);
		
		// Check for a submitted filter condition from the querystring...
		if (isset($_GET['column'])) {
			$this->column = $_GET['column'];
			$this->buildFilter();
		}

	}


	// Build a filter string from the columns[] property.
	public function buildFilter() {

		$filterStr = "";
		foreach ($this->column as $fieldName=>$value) {
			if (strlen($filterStr) > 0) $filterStr .= "&";
			$filterStr .= "column[{$fieldName}]=" ; 
			$filterStr .= $value;
		}

		// Save filter string parameters to the filterStr property.
		$this->filterStr = $filterStr;
		
	}

	public function getRESTurl($resource) {

		debug_msg("REST ROOT: {$GLOBALS['RESTroot']}" , true, "context::GetRESTurl()") ;
		$restURL = str_replace("common", $resource, $GLOBALS['RESTroot']);
		$restURL .= "{$resource}.php/{$resource}";
		debug_msg("REST URL: " . $restURL, true) ;
		return $restURL ;
	}


	public function render($record=null) {
	
		// Capture the html for this component from its template.
		$html = file_get_contents($this->template, FILE_USE_INCLUDE_PATH);
		
		// Be sure to build the filter string from columns[]...
		$this->buildFilter();

		// Substitute property values into the HTML segment.
		$html = str_replace("{contextId}", $this->id, $html);
		$html = str_replace("{filterStr}", $this->filterStr, $html);
		$html = str_replace("{persistentFilter}", $this->persistentFilter, $html);
		$html = str_replace("{htmlId}", $this->id, $html);
		$html = str_replace("{keyFieldName}", $this->keyFieldName, $html);
		$html = str_replace("{lastPage}", $this->lastPage, $html);
		$html = str_replace("{limit}", $this->limit, $html);
		$html = str_replace("{pageNo}", $this->pageNo, $html);
		$html = str_replace("{perPage}", $this->perPage, $html);
		$html = str_replace("{resource}", $this->resource, $html);
		$html = str_replace("{restURL}", $this->restURL, $html);
		$html = str_replace("{thisURL}", $this->thisURL, $html);
		$html = str_replace("{sortBy}", $this->sortBy, $html);

		// Capture the current user if available.
		$authStr = '""';
		if (isset($_SESSION['auth'])) {
			$authStr = json_encode($_SESSION['auth']);
		}
		$html = str_replace("{auth}", $authStr, $html);

		return $html;		
	}


	// Create a query string of parameters based on the current context.
	public function queryStr() {
	
		// Begin with any persistent filter condition.
		if (strlen($this->persistentFilter) > 0) $queryStr = $this->persistentFilter;

		// Build a filter condition...
		$this->buildFilter();
		if (strlen($this->filterStr) > 0){
			if (strlen($queryStr) > 0) $queryStr .= "&";
			$queryStr .= $this->filterStr;
		}

		// Add the remaining properties for the context.
		$parameters = array();
		if ($this->limit > 0) {$parameters['limit']=$this->limit;}
		if ($this->pageNo > 1) {$parameters['pageNo']=$this->pageNo;}	
		if ($this->perPage > 0) {$parameters['perPage']=$this->perPage;}
		if ($this->sortBy > "") {$parameters['sortBy']=$this->sortBy;}
		
		$queryString = http_build_query($parameters);
		return $queryString;

	}
 }