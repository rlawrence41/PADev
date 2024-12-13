<?php

/*
 *	payment.class.php - These classes are specific to the payment resource.
 */

// Define a page component.
class paymentPage extends page {
	
	public function __construct() {
		
		parent::__construct("payment", 
							"paymentPage", 
							"Manage Payments");
		
		// Add a resource specific script.
		$this->scripts[] = "ui/js/payment.js";

		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"payment", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage Payments", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new paymentTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new paymentForm("paymentForm", "adminForm", "payment");
		$this->addChild($form); 

	}
}


// Define a table component.
class paymentTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("payment", "id", "payments");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","payment Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("payment_id","payment Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
#		$this->addColumn("id","Id");
		$this->addColumn("transDate","Trans Date");
#		$this->addColumn("transEvent","TransEvent");
		$this->addColumn("transType","Trans Type");
		$this->addColumn("accountNo","Account No");
		$this->addColumn("accountIdSearch","Account Name");
#		$this->addColumn("acctType","AcctType");
#		$this->addColumn("amount","Amount");
		$this->addColumn("payAmount","Payment Amount");
		$this->addColumn("docNo","Document No");
#		$this->addColumn("orderKey","OrderKey");
		$this->addColumn("orderNo","Order No");
#		$this->addColumn("itemNo","ItemNo");
#		$this->addColumn("receiptNo","ReceiptNo");
#		$this->addColumn("specNo","SpecNo");
#		$this->addColumn("lExported","LExported");
		$this->addColumn("comment","Comment");
#		$this->addColumn("updatedBy","UpdatedBy");
#		$this->addColumn("userNo","UserNo");
		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class paymentForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"payment Id", "");
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
		$this->addField("text", "transDate", "TransDate");
		$this->addField("readonly", "transEvent", "TransEvent");
		$this->addField("text", "transType", "TransType");

		$this->addField("hidden", "accountNo", "Account Number");

		// The searchField component needs more explanation...
		$searchField = new searchField("accountNo", 				// Name of the field to be saved in this instance
					"Account Name", 								// Label for the search field (appears in the form)
					"Enter an ID, company, last name, or email",	// Description (used as a placeholder)
					"contact");										// Resource to search

		$this->addChild($searchField);
		
#		$this->addField("readonly", "accountIdSearch", "Current Account Name");
#		$this->children['accountIdSearch']->doNotSave();

		$this->addField("readonly", "acctType", "AcctType");
		$this->addField("hidden", "amount", "Amount");

		// The payment amount is saved as a negative to the amount field.
		$this->addField("text", "payAmount", "Payment Amount");
		$this->addAction("payAmount", "onchange='updateAmount(this.value)'");
		$this->children['payAmount']->doNotSave();
		
		$this->addField("text", "docNo", "Document No.");
		$this->addField("hidden", "orderKey", "OrderKey");

		// Order No. is not saved.
		$this->addField("text", "orderNo", "OrderNo");
		$this->children['orderNo']->doNotSave();

		$this->addField("hidden", "itemNo", "ItemNo");
		$this->addField("hidden", "receiptNo", "ReceiptNo");
		$this->addField("hidden", "specNo", "SpecNo");
		$this->addField("hidden", "lExported", "LExported");
		$this->addField("textarea", "comment", "Comment");
		$this->addField("readonly", "updatedBy", "UpdatedBy");
		$this->addField("readonly", "userNo", "UserNo");
		$this->addField("readonly", "lastUpdated", "LastUpdated");


	}

}


