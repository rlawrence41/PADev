<?php

/*
	The PageNav class will generate a set of pageControls to allow the user
	to navigate to other sets of records.  The page controls will generally
	select records that are near those on the current page.
	
	The names are a little confusing, but... 

	The pageControl class defines each control presented in the pageNav 
	(page navigation) control.  Each control allows you to select which
	page (or set of records) you want to see on the web page.

	Page Navigation refers to the entire set of pageControls.  Together, 
	they provide options for the user to navigate to another "page" (or set 
	of records).

	The top-level component for the administrative application is
	the "page" class.  It basically assembles everything that appears on the
	web page.

	So, the "page" object will include the "pageNav" control, which will be 
	comprised of "pageControls".

 */

class pageNav extends webObj{
	public $template = "pgNav.html";
	public $tableTop = null;
	
	public function __construct(...$args){
		parent::__construct(...$args);

		// Add the page controls.
		$pageControl = new pageControl("goToPage", "pnc0", "Go To Page", "Enter a page you want to go to.");
		$pageControl->template = "pgctlGoTo.html";
		$this->addChild($pageControl);
		$pageControl = new pageControl("firstPage", "pnc1", "1", "Go to page 1.");
		$this->addChild($pageControl);
		$pageControl = new pageControl("previousPage", "pnc2", "<<", "Go to the previous page.");
		$this->addChild($pageControl);
		$pageControl = new pageControl("currentPage", "pnc3", "1", "Current page.");
		$pageControl->template = "pgctlCurrent.html";
		$this->addChild($pageControl);
		$pageControl = new pageControl("nextPage", "pnc4", ">>", "Go to the next page.");
		$this->addChild($pageControl);
		$pageControl = new pageControl("lastPage", "pnc5", "1", "Go to the last page.");
		$this->addChild($pageControl);

		// Add the tableTop controls...
		// The table top is a banner (<div>) that spans the top of the table.
		// It presents information about the table, like the current filter and sort order.
		// It seems like it should belong to the table, but it must be rendered here
		// (after the pageNav control) to prevent duplication when the table is 
		//	refreshed.
		$this->tableTop = new tableTop();

	}


	// The container for the application will share its context with this
	// component.
	public function addContext($contextObj){
		$this->context = $contextObj;

	}


	public function refreshControls(){
		
		// Assign the pertinent context variables to the page controls.
		$this->children['currentPage']->title = strval($this->context->pageNo);
		$this->children['lastPage']->title = strval($this->context->lastPage);
				
		// Write the context to the tableTop displays.
		// These long references are the down side of this class hierarchy.
		// For convenience, grab the display objects.
		$filterDisplay = $this->tableTop->children["filterDisplayCol"]->children["filterDisplay"];
		$sortDisplay = $this->tableTop->children["filterDisplayCol"]->children["filterDisplay"];

		// Set the initial displays {using the title attribute} based on the context.
		if (strlen($this->context->filterStr) > 0) $filterDisplay->title = $this->context->filterStr;
		if (strlen($this->context->sortBy) > 0) $sortByStr = $this->context->sortBy;

	}
	

 	public function render($record=null){
	
		// Incorporate the context before rendering.
		$this->refreshControls();
		$html = parent::render();

		// Append the table top rendering to the end of the pageNav html.
		$tableTopHtml = $this->tableTop->render();
		$html .= $tableTopHtml;
		
		return $html;
	}

	// setAddAction() sets the action for the add button for the application.
	// The add button actually belongs to a sub-component.  This procedure 
	// simply makes it easier to set that property.
	public function setAddAction($actionStr){

//	echo "pageNave setAddAction to:  {$actionStr} <br/>\n";
		
		$addButton = $this->tableTop->children['filterControlsCol']->children['addButton'];
		$addButton->enabledAction = $actionStr;
		
	}

}


class pageControl extends webObj{
	public $template = "pgctlEnabled.html";
	public $visible = true;
	public $disabled = false;

	public function toggleDisabled(){
		if ($this->disabled){
			$this->template = "pgctlEnabled.html";
			$this->disabled = false;
		}
		else {
			$this->template = "pgctlDisabled.html";
			$this->disabled = false;			
		}
	}

	public function toggleVisible(){
		if ($this->visible){
			$this->visible = false;
		}
		else {
			$this->visible = false;			
		}
	}

	public function render($record=null) {
		if ($this->visible) {
			return parent::render();
		}
		else {return "";}
	}
}
