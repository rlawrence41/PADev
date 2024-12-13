<?php

/*
 *	transStep.class.php - These classes are specific to the transStep resource.
 */

// Define a page component.
class transStepPage extends page {
	
	public function __construct() {
		
		parent::__construct("transStep", 
							"transStepPage", 
							"Manage transSteps");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"transStep", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage transSteps", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new transStepTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new transStepForm("transStepForm", "adminForm", "transStep");
		$this->addChild($form); 

	}
}


// Define a table component.
class transStepTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("transStep", "id", "transSteps");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","transStep Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("transStep_id","transStep Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("txName","TxName");
		$this->addColumn("stepName","StepName");
		$this->addColumn("txDescription","TxDescription");
		$this->addColumn("txView","TxView");
		$this->addColumn("resource","Resource");
		$this->addColumn("keyFieldName","KeyFieldName");
		$this->addColumn("secondaryKey","SecondaryKey");
		$this->addColumn("parentId","ParentId");
		$this->addColumn("parentKeyField","ParentKeyField");
		$this->addColumn("addAction","AddAction");
		$this->addColumn("exitAction","ExitAction");
		$this->addColumn("selectAction","SelectAction");
		$this->addColumn("summaryBandTemplate","SummaryBandTemplate");
		$this->addColumn("updatedBy","Updated By");
		$this->addColumn("userNo","User No.");
		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class transStepForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"transStep Id", "");
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
		$this->addField("text", "txName", "TxName");
		$this->addField("text", "stepName", "StepName");
		$this->addField("text", "txDescription", "TxDescription");
		$this->addField("text", "txView", "TxView");
		$this->addField("text", "resource", "Resource");
		$this->addField("text", "keyFieldName", "KeyFieldName");
		$this->addField("text", "secondaryKey", "SecondaryKey");
		$this->addField("text", "parentId", "ParentId");
		$this->addField("text", "parentKeyField", "ParentKeyField");
		$this->addField("text", "addAction", "AddAction");
		$this->addField("text", "exitAction", "ExitAction");
		$this->addField("text", "selectAction", "SelectAction");
		$this->addField("text", "summaryBandTemplate", "SummaryBandTemplate");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


