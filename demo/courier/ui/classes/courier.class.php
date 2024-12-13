<?php

/*
 *	courier.class.php - These classes are specific to the courier resource.
 */

// Define a page component.
class courierPage extends page {
	
	public function __construct() {
		
		parent::__construct("courier", 
							"courierPage", 
							"Manage Courier Services");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"courier", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage Courier Services", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new courierTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new courierForm("courierForm", "adminForm", "courier");
		$this->addChild($form); 

	}
}


// Define a table component.
class courierTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("courier", "id", "couriers");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","courier Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("courier_id","courier Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		#$this->addColumn("id","Id");
		$this->addColumn("courier","Courier");
		$this->addColumn("lUSPS","Is USPS?");
#		$this->addColumn("scac","Scac");
#		$this->addColumn("svcLevel","SvcLevel");
		$this->addColumn("chargeType","Charge Type");
		$this->addColumn("fixedAmt","Fixed Amt");
		$this->addColumn("threshold","Threshold");
		$this->addColumn("variableAmt","Variable Amt");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User");
#		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class courierForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"courier Id", "");
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
		$this->addField("text", "courier", "Courier");
		$this->addField("checkbox", "lUSPS", "Is this a U.S. Postal Service?");
		$this->addField("text", "scac", "Scac");
		$this->addField("text", "svcLevel", "Service Level");
		$chargeTypeField = $this->addField("select", "chargeType", "Charge Type");
			$chargeTypeField->options = ["FLAT FEE", "PERCENT", "WEIGHT", "COUNT", "BASE+%"];
			$chargeTypeField->addOptions("FLAT FEE");  //Sets the default.
		$this->addField("text", "fixedAmt", "Fixed Amount");
		$this->addField("text", "threshold", "Threshold");
		$this->addField("text", "variableAmt", "Variable Amount");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


