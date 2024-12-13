<?php

/*
 *	code.class.php - These classes are specific to the code resource.
 */

// Define a page component.
class codePage extends page {
	
	public function __construct() {
		
		parent::__construct("code", 
							"codePage", 
							"Manage codes");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"code", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage codes", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new codeTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new codeForm("codeForm", "adminForm", "code");
		$this->addChild($form); 

	}
}


// Define a table component.
class codeTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("code", "id", "codes");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","code Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("code_id","code Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("code","Code");
		$this->addColumn("codeType","CodeType");
		$this->addColumn("descript","Descript");
		$this->addColumn("updatedBy","updatedBy");
		$this->addColumn("userNo","User No.");
		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class codeForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"code Id", "");
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
		$this->addField("readonly", "id", "Id");
		$this->addField("text", "code", "Code");
		$codeTypeField = $this->addField("select", "codeType", "Code Type");
			// Assign a list of options and define which should be selected.
			$codeTypeField->options = ["customer", "marketing"];
			$codeTypeField->addOptions("customer");
		$this->addField("textarea", "descript", "Descript");
		$this->addField("readonly", "updatedBy", "updatedBy");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


