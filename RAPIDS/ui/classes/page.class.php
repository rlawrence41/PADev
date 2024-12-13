<?php

/*
 *		page.class.php -- This is the container class for the page of a 
 *						single-page web application.
 */

class page extends webObj{

// Container class for the page.

	public $template = "pageBootstrap.html";
	public $context = null;
	public $scripts = array();
		
	function __construct(...$args) {
		
		parent::__construct(...$args);
		
		// The standard menu works the same for different applications.
		$menu = new standardMenu("menu");
		$this->addChild($menu);
		$this->scripts=array("/common/ui/js/pageNav.js",
							"/common/ui/js/searchList.js",
							"/common/ui/js/pageActions.js",
							"/common/ui/js/auth.js",
							"/common/ui/js/pledge.js");
		
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

	// Turn the list of scripts associated with the page into HTML.
	public function gatherScripts(){
	
		$scriptsHTML = "" ;
		foreach ($this->scripts as $key=>$script){
			$scriptsHTML .= "	<script src='{$script}'></script>\n";
		}
		return $scriptsHTML;
	}

	public function render($record=null){

		// This page container renders the context for the application 
		// separately, so it can be placed in the appropriate location in the 
		// template.
	
		if (!is_null($this->context)){$contextHTML = $this->context->render();}
		else {$contextHTML = "";}
		$html = parent::render();
		$html =str_replace("{context}", $contextHTML, $html);
		$scriptsHTML = $this->gatherScripts();
		$html =str_replace("{scripts}", $scriptsHTML, $html);
		
		return $html;
	
	}

}
