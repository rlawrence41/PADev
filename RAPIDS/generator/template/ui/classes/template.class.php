<?php

/*
 *	<<resource>>.class.php - These classes are specific to the <<resource>> resource.
 */

// Define a page component.
class <<resource>>Page extends page {
	
	public function __construct() {
		
		parent::__construct("<<resource>>", 
							"<<resource>>Page", 
							"Manage <<resource>>s");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"<<resource>>", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage <<resource>>s", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new <<resource>>Table();
		$table->context = $context;
		$this->addChild($table);

		$form = new <<resource>>Form("<<resource>>Form", "adminForm", "<<resource>>");
		$this->addChild($form); 

	}
}


// Define a table component.
class <<resource>>Table extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("<<resource>>", "id", "<<resource>>s");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","<<resource>> Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("<<resource>>_id","<<resource>> Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
<<tableColumns>>

	}
}


// Define a form component.
class <<resource>>Form extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"<<resource>> Id", "");
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
<<formFields>>

	}

}


