<?php

/*
 *		widget.class.php -- This is the container class for a callable 
 *							application.
 */

class widget extends webObj{

// Container for an application.

	public $template = "widget.html";
	public $context = null;
		
 	function __construct($resource, $keyFieldName, $title="", $description="") {

		// Add the resource classes and path.
		includeResource($resource);

		// Standard instance.
		parent::__construct($resource, "", $title, $description);

		// Set parameters based on the submitted resource.
		if ($title == "") $title = $resource ;
#echo "Widget Title: {$this->title}";
#echo "<br/>\n";
		
		// Assign the resource and the keyfield to the context.
		$context = new context($resource, $keyFieldName) ;
		$this->context = $context ;

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								$description, 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		// Add a table that is specific to the resource.
		$tableClass = "{$resource}Table" ;
		$table = new $tableClass();
		$table->context = $context;
		$this->addChild($table);
		
		// Save the table to the $_SESSION[] object...
		$_SESSION[$tableClass] = $table;
		
		// Add a summary band...
		$summaryBand = new summaryBand();
		$this->addChild($summaryBand);

		// Add a form that is specific to the resource.
		$formClass = "{$resource}Form" ;
		$form = new $formClass("{$resource}Form", "adminForm", $this->title);
		// Add the widget title to the form.  The same form may be used in multiple
		// transaction wizards.
#		$form->title = $this->title;
		$this->addChild($form); 

#echo "Widget Form Title: {$form->title}";
#echo "<br/>\n";

	}
 

	// Context will be shared among multiple components.
	public function addContext($contextObj){
		$this->context = $contextObj;
	}

	// Allow an external procedure to add a sub-classed form.
	public function addForm($formObj){
		$this->addChild($formObj);
		$this->form = $formObj;
	}

	// Allow an external procedure to add a sub-classed table.
	public function addTable($tableObj){
		$this->addChild($tableObj);
		$this->table = $tableObj;
	}

	public function render($record=null){

		// Add the resource classes and path, so the templates can be found.
		includeResource($this->context->resource);
		
		// Render the context.
		$contextHTML = $this->context->render();

		$html = parent::render();
		$html =str_replace("{context}", $contextHTML, $html);

		return $html;
	
	}

}


class summaryBand extends webObj{
	
	public $name = "summaryBand";
	public $htmlId = "summaryBandId";
	public $template = "summaryBand.html";
	
}