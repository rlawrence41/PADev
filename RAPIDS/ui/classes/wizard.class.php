<?php

/*
 *		wizard.class.php -- This is the container class for a callable 
 *							application.
 
	The wizard is a collection of steps for the resources included in the 
	transaction.
	
	Those steps will present a widget application for the resource.  
	A filter may be set on the resource by the wizard to limit the records 
	presented, to those belonging to the transaction.  An example might 
	be the items that belong to a customer order.

	After considerable floundering around, I finally nailed down the following
	steps and requirements for the wizard process.
	
    1. the launchStep() javascript function, which needs to formulate a URL to call...
    2. the wizard php procedure, which must understand the context to render …
    3. the wizard step and supporting resource widget (application), which when complete must update…
    4. the transaction record, which in turn launches,
    5. the report for the transaction resource key, which renders the elements to call launchStep()…
 
	The parameters needed by the above steps:
        1. launchStep needs
            a. The transaction resource
            b. the name of the step being called (implies the supporting resource)
            c. the field name and value to set a filter on the supporting resource.
        2. The transaction wizard needs:
            a. the step name,
            b. refers to the supporting resource,
            c. the filter condition,
            d. renders the widget for the supporting resource.
        3. The widget sets the filter, allows the CRUD operation(s) on the supporting 
			resource.  Upon completion (must be a javascript event), the widget should 
			execute a REST call to update the transaction record.  So, it needs:
            a. The transaction resource and key value for the transaction,
            b. the related field and value to update.
        4. Upon successful completion of the update, the widget needs the transaction 
			resource and key value to refresh the transaction and launches...
        5. the transaction report with launchStep() function rendered.


	*****************************  ATTENTION!!!  *******************************

	This wizard class looks a lot like the page class, but it works differently.
	The page class is concerned with NOT disrupting the user experience by 
	refreshing the entire document.  Instead, it seeks the data requested, and 
	updates the table in the page--keeping the remainder of the page stable.

	By contrast, the wizard is launched by the transaction report.  The report
	will render specific launchStep() actions for regions of the report.  The 
	wizard is rendered for a specific step.  When the step is completed, the 
	transaction report is refreshed.  Thus, the wizard, when called WILL REFRESH
	THE ENTIRE HTML DOCUMENT!

	Another major point of this scenario is that the context of the wizard Step
	is not available at the time the report is rendered.  However, the 
	relationship to each supporting resource is understood at the time the report
	is designed.  Consequently, launchStep() IS RENDERED EXPLICITLY IN THE REPORT 
	TEMPLATE.

	*****************************************************************************
 */

class wizard extends webObj{

	public $column = array() ;			// Holds fields and values for a filter condition.
	public $resource = "" ;				// Resource for this transaction.
	public $parentResource = "" ;		// Holds the resource for the parent of this transaction.
	public $keyFieldName = "id" ;		// Key field for this resource.
	public $persistentFilter = "" ;		// A filter that should always be present.
	public $keyValue = 0;				// Holds the key value for the selected transaction.
	public $steps = array();			// List of steps for this tranaction.
	public $currentStep = "" ;			// Determines which wizard step to render.
	public $menu = null ;				// Menu options will present the wizard steps.
	public $scripts = array();			// Holds the list of javascript procedures to include.
	public $count = 0;					// Holds the count of records in the result set.
	public $resultSet = array();		// Holds the current transaction data.
	public $errorMsg = "";				// Holds an error message--particularly from JSON.
	public $template = "wizardBootstrap.html";

	// Standard parameters: Name, Id, Title, Description, Action.
	function __construct(...$args) {
		
		parent::__construct(...$args);		
		
		// Give the menu a name for later reference.
		$menuObj = new menu() ;
		$menuObj->title = $this->title . " Menu" ;
		$this->menu = $menuObj ;
		

		if (isset($_GET["parentKey"])) {
			$this->keyValue = $_GET["parentKey"] ;
			
			// Now I can capture the transaction.
			$this->getTransaction() ;
		}

		// If a step has been submitted, set the current step.
		if (isset($_GET["step"])) {
			$this->currentStep = $_GET["step"] ;
		}			
	}


	// AddStep() adds the wizard steps to the wizard.  Each step is for a supporting
	// resource and refers back to the related key in the parent resource.

	//	Sample call:
	//  	$this->addStep("contact", "id", "shipToNo", "Ship To", 
	//						"Select or enter the ship to contact for this customer order.")

	public function addStep($resource, $keyFieldName, $parentKeyField, $title, $description="") {
				

		// Instantiate the wizard step.
		$wizardStep = new wizardStep($resource, $keyFieldName, $title, $description) ;
		$wizardStep->name = $title;						// Use the title to uniquely name the step.
		$wizardStep->resource = $resource ;				// the supporting resource.
		$wizardStep->keyFieldName = $keyFieldName;		// key value sought or filtered for the supporting resource.
		$wizardStep->parentResource = $this->parentResource; // The resource for the parent of the transaction.
		$wizardStep->parentKeyField = $parentKeyField;	// Related key field in the parent resource.
		$wizardStep->transResource = $this->resource;	// The view for the transaction.

#debug_msg("Adding Wizard Step: {$title}, for resource, {$resource}. ", true, "addWizardStep()");

		// Add the step to the list of steps in the wizard;
		$this->steps[$title] = $wizardStep ;

		// The value used to filter the supporting resource comes from the transaction.
		// The resultSet is an array of records--though there should only be a single
		// record in it.
		
		// Add the step to the wizard menu.
		$menuObj = $this->menu ;
		$menuItem = new menuItem($title, "", $title, $description );

		// Add the launchStep action to the menu.
		
		// Make sure we have a refreshed transaction to refer to.
		$this->getTransaction();
		$keyValue = $this->resultSet[0][$parentKeyField]; 

#echo "Key Value: {$parentKeyField} = {$keyValue}<br/>\n";		

		$action = $this->buildAction($title, $keyFieldName, $keyValue);
		$menuItem->action = $action ;
		$menuObj->addChild($menuItem);	
		
		// Return the wizard step object.
		return $wizardStep;

	}


	// Build a javascript onclick action to call launchStep().
	private function buildAction($stepName, $keyFieldName, $keyValue){

		// Javascript function launchStep() parameters:
/*        1. launchStep needs
            a. The transaction resource (is already assigned to the wizard),
			b. the key value for the transaction,
            b. the name of the step being called (implies the supporting resource),
            c. the key field name in the supporting resource, and  
			d. the value to set a filter in the supporting resource.
 */

		// Build up the action string...
		$action = "onclick='launchStep(" ;
		$action .= "\"{$this->keyValue}\"," ;		// Key value for the parent.
		$action .= "\"{$stepName}\"," ;				// The Step name		
		$action .= "\"{$keyFieldName}\"," ;			// Key field in the supporting resource.
        $action .= "\"{$keyValue}\"" ;				// Key value to filter on.
		$action .= ")'" ;
		
		return $action;
		
	}


	// Render list of scripts associated with the wizard into HTML.
	public function gatherScripts(){

		// Add the scripts needed for user interaction.
		$this->scripts=array("/common/ui/js/pageNav.js",
							"/common/ui/js/searchList.js",
							"/common/ui/js/pageActions.js",
							"/common/ui/js/auth.js",
							"/common/ui/js/pledge.js",
							"/common/ui/js/wizard.js");

		// If a transaction-specific script is available, add it to the list.
		$resource = $this->resource;
		$transactionjs = "ui/js/{$resource}.js" ;
		if (file_exists($transactionjs)) {
			$this->scripts[]=$transactionjs ;
		}
		
		// If a resource-specific script is available, add it to the list.
		$step = $this->steps[$this->currentStep];
		$resource = $step->resource;
		// Note, this procedure executes a level down from the document root.
		// So, the parent directory symbol is needed to satisfy file_exists().
		$stepjs = "../{$resource}/ui/js/{$resource}.js" ;
		if (file_exists($stepjs)) {
			$this->scripts[]=$stepjs ;
		}
	
		$scriptsHTML = "" ;
		foreach ($this->scripts as $key=>$script){
			$scriptsHTML .= "	<script src='{$script}'></script>\n";
		}
		return $scriptsHTML;
	}

	
	// Make sure the transaction is available when adding steps
	// to the wizard.

	public function getTransaction() {

		// Only needed if the resultSet is empty.
		if (count($this->resultSet) > 0) return ;
	
		$resource = $this->resource ;
		$queryStr = "?column[{$this->keyFieldName}]={$this->keyValue}";
		if (strlen($this->persistentFilter) > 0) {
			$queryStr .= "&" . $this->persistentFilter;
		}


		// The global variable RESTroot will contain the resource for the parent
		// transaction.  Replace it with the supporting resource.
		$restURL = $this->getRESTurl($resource);

		// Add the resource rest PHP procedure and query string.
		$url = $restURL . $queryStr ;
		debug_msg("REST API Call: " . $url, true, "Wizard->GetTransaction()");

		// Sample REST call for transaction:
		//https://dev.pubassist.com/customerOrder/rest/customerOrder.php?column[id]=14

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


	public function render($record=null){

		$html = parent::render();
		
		// Render the current step only.
		$wizardStep = $this->steps[$this->currentStep] ;
		$stepHtml = $wizardStep->render() ;
		$html =str_replace("{wizardStep}", $stepHtml, $html);

		// Substitute the menu.
		$menuHtml = $this->menu->render();
		$html =str_replace("{menu}", $menuHtml, $html);
	
		// Substitute the scripts.
		$scriptsHtml = $this->gatherScripts();
		$html =str_replace("{scripts}", $scriptsHtml, $html);

		// Substitute the current transaction.
		$transactionHtml = json_encode($this->resultSet[0]);
		$html =str_replace("{transaction}", $transactionHtml, $html);
		
		return $html;
	
	}
}



/*
	The wizard step will instance a widget application for a particular resource.
	That widget will not exist until the wizard step is rendered.

	The widget application is the same application and class as the 
	admin application for the resource.  It simply runs within a widget container--as opposed
	to a page--and it runs in the context of the transaction wizard.
	
	Each step in the wizard will likely result in updating the parent record--either by
	saving the id of the selected supporting resource, or other calculations based on 
	the assigned records in the supporting resource.
	
	Once that update has been completed, the transaction can then be re-selected and 
	presented as a report.

 */


class wizardStep extends webObj{
	
	public $template = "wizardStep.html";	// HTML template for the wizard step.
	public $summaryBandTemplate = "summaryBand.html";  // Wizard steps frequently require summary data and actions.

	public $column = array() ;		// Holds fields and values for a filter condition.
	public $resource = "" ;			// Resource for this step.
	public $keyFieldName = "" ;		// Key field for this resource.
	public $parent = false ;		// Does this step hold the parent record?
	public $parentResource = "";	// Name of the parent resource.
	public $parentKeyField = "";	// Related key field name in the parent.
	public $parentKey = "";			// Key field value from the parent.
	public $persistentFilter = "" ;	// A filter that should always be present.
	
	public $addAction = "";			// This is the action for the add button.
	public $exitAction = "" ;		// The action for exiting this wizard step.
	public $launchAction = "";		// The javascript action to launch the wizard step.
	public $postProcess = "";		// Procedure to execute at post-processing time.
	
	public $select = false ;		// Should the selection icon be presented in the table?
	public $selectAction = "" ; 	// The action to assign to the selection icon.
	


	public function __construct($resource, $keyFieldName, $title, $description="") {

		// Assign the title as the name of this wizard step.
		// The step name is how separate steps can be defined for the same resource.
		$this->name = $title ;
		
		$this->resource = $resource ;
		$this->keyFieldName = $keyFieldName ;
		$this->title = $title ;
		$this->description = $description ;
		
		// Capture the parent resource and key value from the query string.
		if (isset($_GET["parent"])) $this->parentResource = $_GET["parent"] ;
		if (isset($_GET["parentKey"])) $this->parentKey = $_GET["parentKey"] ;
		if (isset($_GET["parentKeyField"])) $this->parentKey = $_GET["parentKeyField"] ;

		// Capture the filter from the column[] parameter.
		if (isset($_GET["column"])) $this->column = $_GET["column"] ;

		// Set default actions.
		$actionStr = "exitStep({$this->parentKey},'{$this->title}')";
		$this->exitAction = 'onclick="' . $actionStr . '"';
	
	}


	// Render only the current step.
	
	public function render($record=null){
				
		// Preserve the current include path.
		$savedIncludePath = get_include_path();
		
		// Wait until now to instance the application for the step.
		$widget = new widget($this->resource, 
							$this->keyFieldName, 
							$this->title, 
							$this->description) ;

		// Pass the filter condition to the widget.
		$widget->context->persistentFilter = $this->persistentFilter ;
		$widget->context->column = $this->column ;
		
		// Should the selection icon be activated for the table in this step?
		if ($this->select) $this->setSelectOption($widget);
		
		// Set the action for the "done" button in the widget.
		if (strlen($this->exitAction) > 0){
			$actionStr = $this->exitAction;
			// Substitute the parent key value.
			$actionStr = str_replace("{keyValue}", $this->parentKey, $actionStr);
			$widget->action = $actionStr;
		}
		
		// Set the action for the "Add" button, if provided.
		if (strlen($this->addAction) > 0){

			$actionStr = $this->addAction;
			// Substitute the parent key value.
			$actionStr = str_replace("{keyValue}", $this->parentKey, $actionStr);
			// The add button is rendered in the pageNav class.
			$pageNav = $widget->children["pageNav"];
			$pageNav->setAddAction($actionStr);
		}

		// Should the summary band be activated?
		$summaryBand = $widget->children["summaryBand"];
		$summaryBand->template = $this->summaryBandTemplate;
	
		// Render all...
		$html = $widget->render();
		
		// Restore the path setting.
		set_include_path($savedIncludePath);

		return $html;	
	}	

/*

	Re-using the admin application components has worked very well for the transaction
	wizard with one exception:  Refreshing the table.  The wizard step will likely 
	specify the selection options for the table.  However, the <resource>Table.php 
	procedures, called by the javascript function refreshTable(), will know nothing 
	about these selection properties.
	
	After considerable trial and error, it seems that saving these selection options to 
	the $_SESSION object is the best alternative to persist them through table refreshes.  
	These properties can only be reinstated after the table has been instanced; and the 
	table is not instanced until it is rendered in the wizard step.

*/

	// Set the selection properties for the wizard step table.
	public function setSelectOption($widget) {
		
		$tableName = $this->resource . "Table" ;
		$table = $widget->children["{$tableName}"];
		$table->select = true;
		$table->selectAction = $this->selectAction;

		// Save the selection properties to the $SESSION[] object.
		$tableProperties = array("step" => $this->name,
								"resource" => $this->resource,
								"selectAction" => $this->selectAction
								);
		$_SESSION['tableProperties'] = $tableProperties;

	}

}

