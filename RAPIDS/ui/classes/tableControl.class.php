<?php

// The tableControl class is a container for the table and page navigation
// components.  It is separated here to make it useable by both the page
// and refreshTable applications.

class tableControl extends webObj{
	public $template = "tableControl.html";
	public $context = null;

	public function __construct(...$args){

		parent::__construct(...$args);
		
		//  Add the page navigation control 
		$pageNav = new pageNav("pageNav", "pageNav1");
		$this->addChild($pageNav);
	}

	
	// The container for the application MUST share its context with 
	// this component.
	public function addContext($contextObj){
		$this->context = $contextObj;
		$this->children['pageNav']->addContext($contextObj);
		$this->children['table']->addContext($contextObj);
	}


	// The table is generally specific to the resource as well.
	public function addTable($tableObj){
		$this->addChild($tableObj);
	}
}
