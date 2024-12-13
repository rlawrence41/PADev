<?php

/*
 *	receipt.class.php - These classes are specific to the receipt resource.
 */

// Define a page component.
class receiptPage extends page {
	
	public function __construct() {
		
		parent::__construct("receipt", 
							"receiptPage", 
							"Manage receipts");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"receipt", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage receipts", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new receiptTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new receiptForm("receiptForm", "adminForm", "receipt");
		$this->addChild($form); 

	}
}


// Define a table component.
class receiptTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("receipt", "id", "receipts");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","receipt Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("receipt_id","receipt Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
#		$this->addColumn("customerNo","CustomerNo");
		$this->addColumn("customerNoSearch", "Customer");
		$this->addColumn("recptDate","RecptDate");
#		$this->addColumn("orderKey","OrderKey");
		$this->addColumn("invoiceStr", "Invoice");
		$this->addColumn("amount","Amount");
#		$this->addColumn("lFullPaymnt","LFullPaymnt");
		$this->addColumn("recptType","RecptType");
#		$this->addColumn("crcrdAcct","CrcrdAcct");
#		$this->addColumn("crcrdExpDt","CrcrdExpDt");
#		$this->addColumn("crcrdVCode","CrcrdVCode");
#		$this->addColumn("crcrdAuth","CrcrdAuth");
#		$this->addColumn("transactId","TransactId");
#		$this->addColumn("lItemized","LItemized");
		$this->addColumn("lRefund","LRefund");
#		$this->addColumn("lProcessed","LProcessed");
#		$this->addColumn("lExported","LExported");
#		$this->addColumn("comment","Comment");
		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User No.");
		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class receiptForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"receipt Id", "");
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
#		$this->addField("hidden", "customerNo", "CustomerNo");

		// The searchField component needs more explanation...
		$searchField = new searchField("customerNo", 				// Name of the field to be saved in this instance
					"Customer", 									// Label for the search field (appears in the form)
					"Enter an ID, company, last name, or email",	// Description (used as a placeholder)
					"contact");										// Resource to search

		$this->addChild($searchField);

		$this->addField("text", "recptDate", "RecptDate");
		$this->addField("text", "orderKey", "Order Key");
		$this->addField("text", "amount", "Amount");
		$this->addField("checkbox", "lFullPaymnt", "Consider Payment in Full?");
		$this->addField("readonly", "recptType", "Receipt Type");

		$searchField = new searchField("recptType", 				// Name of the field to be saved in this instance
					"Receipt Type", 								// Label for the search field (appears in the form)
					"Select a receipt type.",						// Description (used as a placeholder)
					"recptype");									// Resource to search

		$this->addChild($searchField);



		$this->addField("text", "crcrdAcct", "CrcrdAcct");
		$this->addField("text", "crcrdExpDt", "CrcrdExpDt");
		$this->addField("text", "crcrdVCode", "CrcrdVCode");
		$this->addField("text", "crcrdAuth", "CrcrdAuth");
		$this->addField("text", "transactId", "TransactId");
		$this->addField("checkbox", "lItemized", "Itemized?");
		$this->addField("checkbox", "lRefund", "Refund?");
		$this->addField("checkbox", "lProcessed", "Processed?");
		$this->addField("checkbox", "lExported", "Exported?");
		$this->addField("textarea", "comment", "Comment");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


