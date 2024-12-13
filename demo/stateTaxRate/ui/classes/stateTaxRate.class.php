<?php

/*
 *	stateTaxRate.class.php - These classes are specific to the stateTaxRate resource.
 */

// Define a page component.
class stateTaxRatePage extends page {
	
	public function __construct() {
		
		parent::__construct("stateTaxRate", 
							"stateTaxRatePage", 
							"Manage stateTaxRates");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"stateTaxRate", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage stateTaxRates", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new stateTaxRateTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new stateTaxRateForm("stateTaxRateForm", "adminForm", "stateTaxRate");
		$this->addChild($form); 

	}
}


// Define a table component.
class stateTaxRateTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("stateTaxRate", "id", "stateTaxRates");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","stateTaxRate Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("stateTaxRate_id","stateTaxRate Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("stateAbbr","StateAbbr");
		$this->addColumn("stateName","StateName");
		$this->addColumn("taxRate","TaxRate");
		$this->addColumn("lTaxShip","Tax Shipping?");
		$this->addColumn("updatedBy","Updated By");
		$this->addColumn("userNo","User No.");
		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class stateTaxRateForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"stateTaxRate Id", "");
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
		$this->addField("text", "stateAbbr", "StateAbbr");
		$this->addField("text", "stateName", "StateName");
		$this->addField("text", "taxRate", "TaxRate");
		$this->addField("checkbox", "lTaxShip", "Tax Shipping?");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


