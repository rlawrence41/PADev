<?php

/* 
 *	getInput - This procedure instantiates an input element.  
 *			This procedure is defined separately because it is used at both
 *			the form and field group levels.
 */

function getInput($fieldType, $fieldName, $title="", $description="") {

	// Make sure that fields are given unique id's.
	$htmlId = uniqid("$fieldName");

	switch ($fieldType) {
		case "checkbox" :
			$field = new inputCheckbox($fieldName, $htmlId, $title, $description);
			break;
		case "date" :
			$field = new inputDate($fieldName, $htmlId, $title, $description);
			break;
		case "email" :
			$field = new inputEmail($fieldName, $htmlId, $title, $description);
			break;
		case "hidden" :
			$field = new inputHidden($fieldName, $htmlId, $title, $description);
			break;
		case "message" :
			$field = new formMessage($fieldName, $htmlId, $title, $description);
			break;
		case "password" :
			$field = new inputPassword($fieldName, $htmlId, $title, $description);
			break;
		case "radio" :
			$field = new inputRadio($fieldName, $htmlId, $title, $description);
			break;
		case "readonly" :
			$field = new inputReadOnly($fieldName, $htmlId, $title, $description);
			break;
		case "select" :
			$field = new inputSelect($fieldName, $htmlId, $title, $description);
			break;
		case "text" :
			$field = new inputText($fieldName, $htmlId, $title, $description);
			break;
		case "textarea" :
			$field = new inputTextarea($fieldName, $htmlId, $title, $description);
			break;
		default :
			$field = new inputText($fieldName, $htmlId, $title, $description);
	}	
	return $field;
}


class form extends webObj{
	public $template = "formModal.html";
	public $launchImmediate = false;
	
	public function __construct($name, $htmlId, $title="") {

		if (empty($title)) {$title = ucfirst($name);}
		parent::__construct($name, $htmlId, $title);

	}

	// Add an action string to submitted field element.
	public function addAction($fieldName, $actionStr){

		$field = $this->children["{$fieldName}"];
		$field->action = $actionStr;
	}


	// e.g. $fieldGroup->addField("Hidden", "contact_no", "contactNo", "Contact Number", "");

	public function addField($fieldType, $fieldName, $title="", $description="") {

		$field = getInput($fieldType, $fieldName, $title, $description);
		$this->addChild($field);
		return $field;
	}

	// Add a field group as a child to the form.
	public function addFieldGroup($groupName, $expand=false){
		$fieldGroup = new fieldGroup($groupName, $expand);
		$this->addChild($fieldGroup);
		
		// Return the field group object to facilitate addField() calls.
		return $fieldGroup;
	}
}


class formMessage extends webObj{
	public $template = "formMessage.html";
}


class fieldGroup extends webObj{
	public $template = "accordionCollapse.html";
	private $groupShow = false;

	// Changing the argument list from the standard webObj...
	public function __construct($name, $groupShow=false){ 
	
		$this->name = $name;
		$this->htmlId = $this->name . "Fg";
		$this->title = ucfirst($this->name);
		$this->description = "Click here to collapse or show the fields in this group.";
		
//		debug_msg("FieldGroup Construction: Group Show set to:  " . ($groupShow ? 'true' : 'false'), "Form.Class");

		// The group is collapsed by default.  Toggle is requested in the call.
		if ($groupShow) {
			$this->groupShow = true;
			$this->toggleCollapse();
			}

	}

	// Exactly the same as for the form.
	public function addField($fieldType, $fieldName, $title="", $description="") {

		$field = getInput($fieldType, $fieldName, $title, $description);
		$this->addChild($field);
		return $field;
	}

	
	public function toggleCollapse(){
		if ($this->groupShow){
			$this->template =  "accordionShow.html";
			$this->groupShow = true;
		}
		else {
			$this->template =  "accordionCollapse.html";
			$this->groupShow = false;
		}
	}	
}


class inputObj extends webObj{
	
	public $value = "";
	public $classList = "data-set";
	

	public function doNotSave(){

		// Remove this input element from the data-set class.
		$this->classList = "";
	
	}

	public function render($record=null) {

		$html = parent::render($record);

		// Add a value attribute and class list if available...
#		$html = str_replace("{valueAttr}", $valueAttr, $html);
		$html = str_replace("{classList}", $this->classList, $html);
		return $html;
	}
}


class inputCheckbox extends inputObj{
	public $template = "inputCheckbox.html";
	
	public function render($record=null){

		// Check the box if it's value is true.
		$html = parent::render();

		// Checkbox elements have a value attribute--rather than the {value} token.
		$valueAttr = "";
		if ($this->value) { $valueAttr = "checked";}
		$html = str_replace("{valueAttr}", $valueAttr, $html);
		return $html;

	}
}
	
class inputDate extends inputObj{
	public $template = "inputDate.html";
}
class inputEmail extends inputObj{
	public $template = "inputEmail.html";
}
class inputHidden extends inputObj{
	public $template = "inputHidden.html";
}
class inputPassword extends inputObj{
	public $template = "inputPassword.html";
}
class inputRadio extends inputObj{
	public $template = "inputRadio.html";
	public $value = "";
	public $classList = "";
	
	public function render($record=null){

		$html = parent::render();

		// Radio buttons have a value attribute--rather than the {value} token.
		$valueAttr = "";
		// Check the radio button if it's value is true.
		if ($this->value) { $valueAttr = "checked";}
		$html = str_replace("{valueAttr}", $valueAttr, $html);
		return $html;

	}
}
class inputReadOnly extends inputObj{
	public $template = "inputReadOnly.html";
}

class inputSelect extends inputObj{
	public $template = "inputSelect.html";
	public $options = array();


	// Add options adds the options list to the dropdown <SELECT> element.  
	// It marks the submitted option value as SELECTED.
	public function addOptions($selectedValue){

		// Add the select options...
		$i = 0;
		foreach ($this->options AS $key=>$value){
			
			$i++ ;
			$name = "option" . strval($i);
			$option = new inputSelectOption($name);
			$option->value = $value;
			if ($value == $selectedValue) $option->selected = true;
			$this->addChild($option);
		}
		
	}

}

class inputSelectOption extends webObj{
	public $template = "inputSelectOption.html";
	public $selected = false;

	public function render($record=null){

		// If the value is the same, mark the option as "selected".
		$selectedAttr = ($this->selected ? "selected" : "");
		$html = parent::render();
		$html = str_replace("{selected}", $selectedAttr, $html);
		return $html;
	}

}


class inputText extends inputObj{
	public $template = "inputText.html";
}
class inputTextarea extends inputObj{
	public $template = "inputTextarea.html";
}






