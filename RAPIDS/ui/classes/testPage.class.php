<?php

/*
 *		testPage.class.php -- This is the container class for a Bootstrap
 *						page.  It is intended for testing other components
 *						that depend on Bootstrap.  This is virtually the same
 *						as the page component, but without the menu.
 */

class testPage extends webObj{

// Container class for the testPage.

	public $template = "pageBootstrap.html";
	public $context = null;

	function __construct(...$args) {
		
		parent::__construct(...$args);
		
		$this->scripts=array("/common/ui/js/pageNav.js",
							"/common/ui/js/searchList.js",
							"/common/ui/js/pageActions.js",
							"/common/ui/js/auth.js",
							"/common/ui/js/pledge.js");
		
	}

		
	// Context will be share among multiple components.
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
