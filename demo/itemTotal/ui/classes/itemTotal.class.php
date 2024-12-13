<?php

/*
 *	itemTotal.class.php - These classes are specific to the itemTotal resource.
 */

// Define a page component.
class itemTotalPage extends page {
	
	public function __construct() {
		
		parent::__construct("itemTotal", 
							"itemTotalPage", 
							"Manage itemTotals");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"itemTotal", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage itemTotals", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new itemTotalTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new itemTotalForm("itemTotalForm", "adminForm", "itemTotal");
		$this->addChild($form); 

	}
}


// Define a table component.
class itemTotalTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("itemTotal", "id", "itemTotals");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","itemTotal Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("itemTotal_id","itemTotal Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("orderKey","OrderKey");
		$this->addColumn("lTaxable","LTaxable");
		$this->addColumn("quantity","Quantity");
		$this->addColumn("subtotal","Subtotal");
		$this->addColumn("shipWeight","ShipWeight");


	}
}


// Define a form component.
class itemTotalForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"itemTotal Id", "");
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
		$this->addField("text", "orderKey", "OrderKey");
		$this->addField("text", "lTaxable", "LTaxable");
		$this->addField("text", "quantity", "Quantity");
		$this->addField("text", "subtotal", "Subtotal");
		$this->addField("text", "shipWeight", "ShipWeight");


	}

}


