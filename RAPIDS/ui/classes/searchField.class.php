<?php 

/* 	Search field is a class designed to facilitate to selection of
 * 	a foreign key.  It does so by presenting a text field and a 
 *	search list.  As the user types a description into the text
 *	field, matching entries for the associated resource are 
 *	presented in a popup (division--not a true popup) for 
 *	selection.  The key value selected is stored in a hidden field
 *	associated with the current (dependent) resource.
 *
 *	An example would be to select a contact as the "payee" in a 
 *	Title Liability Specification (a foreign key to the contact resource).  
 *	The user would enter the contact's name or company into the text
 *	field as a filter to present existing contacts in the search
 *	list.
 *
 *	At first, I attempted to reuse the field class to instance
 *	the foreign key and search fields.  I found that there were
 *	too many unique attributes I wanted to assign.  So, instead,
 *	I've opted for including the field definitions in the template
 *	HTML for this component.
 *

•	I need to clarify my nomenclature:
	o	searchField – is this class for the entire composite component.
	o	foreignKey – is the field in the current resource, frequently a hidden input 
					element.
	o	searchValue – (New name) This is the visible input element where the user can 
					type in a search value.  It will also hold the selected search item
					value when the user selects an item from the searchList.
	o	searchList – This is the <div>/<ul> component that contains the items returned 
					from the search.

•	So, the searchField component will have a foreignKey, searchValue, components as 
	children.

•	It will also have a URL reference to a resource-specific searchList page.

•	The SEARCH Process event sequence:

	1.	The user enters characters into the searchValue element.
	2.	The javascript procedure refreshList() is triggered.
	3.	The keyPress event will call the refreshList() Javascript procedure.  
	4.	If enough characters have been entered, then refreshList() makes an AJAX call 
			to the searchList URL with the current search value.
	5.	The searchList URL will execute a setSearch() function to propagate the search 
			value to the appropriate columns (fields) for the foreign resource.
	6.	It then posts a GET request to the rest URL for the resource with the search 
			value assigned to the appropriate columns.
	7.	It should receive a JSON string of filtered records back from the REST API.
	8.	The searchList URL uses the JSON data to render an HTML div and list.
	9.	The AJAX call will accept the response from the search List URL and update 
			the list element in the active document.
	10.	setSearch() will make the searchList visible.
	11.	The page is back to steady state—waiting for the next user input.

•	The SELECTION process event sequence:

	1.	After narrowing the list down to the desired entry in the foreign resource, 
			the user will click on the entry in the searchList.
	2.	The selectItem() Javascript procedure will assign the value of the selected 
			item to the searchValue input element.  It will assign the selected key 
			value to the hidden foreignKey element.
	3.	selectItem() should also hide the searchList from view.

 */

class searchField extends webObj {
	
	public $template = "searchField.html";	// Holds the HTML template for this component.
	public $foreignKey = null;				// Holds the foreignKey component;
	public $searchValue = null;				// Holds the searchValue component;

	// The constructor should accept similar arguments to a field object.
	public function __construct($name, $title="", $description="", $resource) {


		$htmlId = uniqid("sf");
		parent::__construct($htmlId, $htmlId);
		
		// Add the foreignKey element.
		$fkName = "column[{$name}]";
		$fkId = $htmlId . "FKey";
		$foreignKey = new inputHidden($name, $fkId);
		$this->addChild($foreignKey);
		$this->foreignKey = $foreignKey;
		
		// Add the searchValue element;
		$svId = $htmlId . "Value";
		$svName = $name . "Search";				// Used to load the form for editing!
		$searchValue = new searchValue($svName, $svId, $title, $description);
		$searchValue->action = $this->makeSearchEventHandler($resource);
		$this->addChild($searchValue);
		$this->searchValue = $searchValue;
		
	}

	// Add an action string to foreign key field element.
	public function addAction($fieldName, $actionStr){

		$field = $this->foreignKey;
		$field->action = $actionStr;
	}

	
	// Quotes are a problem in generating the HTML event attribute.
	// So, I'm separating it from the __construct() method...
	private function makeSearchEventHandler($resource){
		$searchURL = $this->getAppUrl($resource) . "SearchList.php";
		$action = "refreshList(this, '{$searchURL}')";
		$attribute = 'onkeyup="' . $action . '"';
		return $attribute ;
	}
}


class searchValue extends webObj{
	
	public $template = "searchValue.html";
}