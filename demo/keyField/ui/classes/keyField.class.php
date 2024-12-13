<?php

/*
 *	keyField.class.php - These classes are specific to the keyField resource.
 */

// Define a page component.
class keyFieldPage extends page {
	
	public function __construct() {
		
		parent::__construct("keyField", 
							"keyFieldPage", 
							"Manage keyFields");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"keyField", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage keyFields", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new keyFieldTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new keyFieldForm("keyFieldForm", "adminForm", "keyField");
		$this->addChild($form); 

	}
}


// Define a table component.
class keyFieldTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("keyField", "id", "keyFields");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","keyField Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("keyField_id","keyField Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
#		$this->addColumn("id","Id");
		$this->addColumn("resource","Resource");
		$this->addColumn("keyField","KeyField");
		$this->addColumn("alias","Alias");
		$this->addColumn("lastKey","LastKey");

	}
}


// Define a form component.
class keyFieldForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"keyField Id", "");
		$fieldGroup->addField("text", 	"company", 		"Company", "Company or organization name");
		$fieldGroup->addField("text", 	"first_name", 	"First Name", "first name");
		$fieldGroup->addField("text", 	"last_name", 	"Last Name", "last name");

		// Sample Standard fields... 
		$this->addField("textarea", 	"po_addr", 		"Street", "Postal Service Street Address ");
		$this->addField("text", 		"city", 		"City", "City or town");
		$this->addField("text", 		"state_abbr", 	"State", "State Abbreviation");
		$this->addField("text", 		"zip_code", 	"Zip Code", "Postal Code");
		*/
		
		// Fields unique to this resource...
		$this->addField("hidden", "id", "Id");
		$this->addField("text", "resource", "Resource");
		$this->addField("text", "keyField", "KeyField");
		$this->addField("text", "alias", "Alias");
		$this->addField("text", "lastKey", "LastKey");


	}

}


