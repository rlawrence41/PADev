<?php

/*
 *	webObj.class.php - This is a new base class for most (if not all) components used 
 *			in this new web development paradigm.
 *
 *			Most of these components do the same thing.  So, it makes sense to do them 
 *			once, and subclass this component to create others.  Those common things 
 *			include the following:
 *
 *			1)	Upon construction, read the arguments (deal with optional arguments) 
 *				and set appropriate properties for the component.
 *			2)	Provide a way to add other components to the list of properties by an 
 *				external procedure.  (e.g. addForm).
 *			3)	Render the component by… 
 *				a)	capturing the html template
 *				b)	inserting child component html
 *				c)	substituting in properties of the component
 *
 */
 
 class webObj {

	public $name = "webObj";
	public $value = "";
	public $htmlId = "webObjId";
	public $title = "";
	public $description = "";
	public $action = "";
	public $template = "noTemplate.html";
	public $children = array();
	public $errorMessage = "";

	public function __construct(...$args) {

		$errorReportingLevel = error_reporting();
		// Supress warning for optional arguments.
		error_reporting(E_ERROR);	// Ignore the warning.
		
		$numargs = func_num_args();

		if ($numargs >= 1) {
			if (func_get_arg(0)) {$this->name = func_get_arg(0);}		// Name
		}
		if ($numargs >= 2) {
			if (func_get_arg(1)) {$this->htmlId = func_get_arg(1);}		// Id
			else {$this->htmlId = $this->name . "Id";}
		}
		if ($numargs >= 3) {
			if (func_get_arg(2)) {$this->title = func_get_arg(2);}		// Title
		}
		if ($numargs >= 4) {
			if (func_get_arg(3)) {$this->description = func_get_arg(3);}// Description
		}
		if ($numargs >= 5) {
			if (func_get_arg(4)) {$this->action = func_get_arg(4);}		// Action
		}
		
		// Restore error reporting to its previous level.
		if ($errorReportingLevel <> 0) { error_reporting($errorReportingLevel);}
	
		// Otherwise, report all errors except E_NOTICE to the default value set in php.ini
		else {error_reporting(E_ALL ^ E_NOTICE);}

		// Make sure descriptions will render in HTML.
		$this->description = htmlspecialchars($this->description, ENT_QUOTES);

	}

	public function addChild($childObj) {
		
		if (is_object($childObj)){ 
			// Add the name as a key, so we can refer to children by name as well.
			$name = $childObj->name;
			$this->children[$name] = $childObj; 
#			$this->children[] = $childObj; 
		}
	}


	// Checks if a folder exist and returns the absolute pathname.
	private function folderExists($folder) {

		// Get canonicalized absolute pathname
		$path = realpath($folder);

		// If it exist, check if it's a directory
		return ($path !== false AND is_dir($path)) ;

	}

	// Returns the application path based on the path of the requesting document.
	/* Examples:
	
	https://app.example.com/contact.php/contactAdmin.php
	https://app.example.com/voterRNC/voterRNCAdmin.php

	 */
	public function getAppUrl($resource) {

		$protocol=isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) 
			? 'https://' : 'http://';
		$hostURL = $protocol . $_SERVER['HTTP_HOST'] ;

		// Most applications are located in the {resource} folder.
		// Some older applciations are located in the document root.
		$path = $_SERVER['DOCUMENT_ROOT'] . $resource ;

		if ($this->folderExists($path)) {
			debug_msg("The Resource Folder exists: " . $path, true, "GetAppUrl") ;
			$appURL = $hostURL . "/{$resource}/{$resource}" ;
			return $appURL;
		}
		
		// The test folder now refers to demo...
		$path = $_SERVER['DOCUMENT_ROOT'] . "demo/" . $resource ;
		if ($this->folderExists($path)) {
			debug_msg("The Test demo/resource Folder exists: " . $path, true, "GetAppUrl") ;
			$appURL = $hostURL . "/demo/{$resource}/{$resource}" ;
			return $appURL;
		}
		
		else {
			debug_msg("Resource application folder does NOT exist: " . $path, true, "GetAppUrl") ;
			$appURL = $hostURL . "/";
		}
		return $appURL ;
	}


	// Returns the RESTroot path based on the path of the requesting document.
	/* Examples:
	
	https://app.example.com/common/rest/contact.php/contact?column[city]=Essex&sortBy=last_name
	https://app.example.com/voterRNC/rest/voterRNC.php/voterRNC?column[CalculatedParty]=1&column[LDName]=338

	 */
	public function getRESTurl($resource) {

		debug_msg("REST root: " . $GLOBALS['RESTroot'], true, "webObj::GetRESTurl()") ;
		$restURL = str_replace("common", $resource, $GLOBALS['RESTroot']);
		$restURL .= "{$resource}.php/{$resource}";

		debug_msg("REST URL: " . $restURL, true, "webObj::GetRESTurl()") ;
		return $restURL ;
	}


	public function render($record=null) {
	
	//  This procedure renders the html for this component and its child components.
	//	Some classes are rendered based on the content of a submitted record.
	
		$content = $this->renderChildren($record);
		$html = $this->renderThis();
		$html = str_replace("{content}", $content, $html);
# echo "Rendering {$this->name}.  Content length: " . strlen($html) . ", template: {$this->template}. <br/>\n";
		return $html;		
	}
	
	public function renderThis() {

		// There is a default template, but if the template has been overwritten
		// with an empty string, there is nothing to render.
		if (strlen($this->template) == 0) return "";

		// Capture the html for this component from its template.
#echo "Web Object {$this->name}, template: {$this->template}<br/>\n";
		$html = file_get_contents($this->template, FILE_USE_INCLUDE_PATH);
		$html = str_replace("{name}", $this->name, $html);
		$html = str_replace("{title}", $this->title, $html);
		$html = str_replace("{htmlId}", $this->htmlId, $html);
		$html = str_replace("{description}", $this->description, $html);
		$html = str_replace("{action}", $this->action, $html);
		$html = str_replace("{error}", $this->errorMessage, $html);
		$html = str_replace("{value}", $this->value, $html);

//echo "webObj render: {$this->name} = {$this->value}<br/>\n";

		return $html;		
		
	}
	
	public function renderChildren($record=null) {

		$html = "";
		foreach ($this->children as $childObj) {
			$html .= $childObj->render($record);
		}
		return $html;
	}
	
 }