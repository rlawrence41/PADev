<?php

/*
 *	ledger.class.php - These classes are specific to the ledger resource.
 */

// Define a page component.
class ledgerPage extends page {
	
	public function __construct() {
		
		parent::__construct("ledger", 
							"ledgerPage", 
							"Manage Ledger Entries");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"ledger", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage Ledger Entries", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new ledgerTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new ledgerForm("ledgerForm", "adminForm", "ledger");
		$this->addChild($form); 

	}
}


// Define a table component.
class ledgerTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("ledger", "id", "ledgers");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","ledger Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("ledger_id","ledger Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("transDate","Transaction Date");
		$this->addColumn("transEvent","Transaction Event");
		$this->addColumn("transType","Transaction Type");
		$this->addColumn("accountNo","Account#");
		$this->addColumn("acctType","Acct Type");
		$this->addColumn("amount","Amount");
		$this->addColumn("docNo","Document#");
		$this->addColumn("orderKey","Order Key");
		$this->addColumn("itemNo","Item#");
		$this->addColumn("receiptNo","Receipt#");
		$this->addColumn("specNo","Spec#");
#		$this->addColumn("lExported","Exported?");
#		$this->addColumn("comment","Comment");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User#");
		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class ledgerForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"ledger Id", "");
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
		$this->addField("text", "transDate", "Transaction Date");

#		$this->addField("text", "transEvent", "TransEvent");
		
		$transEventField = $this->addField("select", "transEvent", "Transaction Event");
			// Assign a list of options and define which should be selected.
			$transEventField->options = ["ORDER", 
										"RETURN",
										"RECEIPT",
										"P.O.",
										"PAYMENT"
										];
			$transEventField->addOptions(NULL);

#		$this->addField("text", "transType", "TransType");

		$transTypeField = $this->addField("select", "transType", "Transaction Type");
			// Assign a list of options and define which should be selected.
			$transTypeField->options = ["Order Total",
										"Return Total",
										"Amount Received",
										"Royalty Fee", 
										"Consignment Cost",
										"Commission"
										];
			$transTypeField->addOptions(NULL);


		$this->addField("text", "accountNo", "Account Number");
		$this->addField("text", "acctType", "Account Type");
		$this->addField("text", "amount", "Amount");
		$this->addField("text", "docNo", "Document Number");
		$this->addField("text", "orderKey", "Order Key");
		$this->addField("text", "itemNo", "Item Number");
		$this->addField("text", "receiptNo", "Receipt Number");
		$this->addField("text", "specNo", "Specification Number");
		$this->addField("checkbox", "lExported", "Exported?");
		$this->addField("text", "comment", "Comment");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User Number");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


