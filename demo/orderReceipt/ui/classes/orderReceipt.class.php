<?php

/*
 *	orderReceipt.class.php - These classes are specific to the orderReceipt resource.
 */

// Define a page component.
class orderReceiptPage extends page {
	
	public function __construct() {
		
		parent::__construct("orderReceipt", 
							"orderReceiptPage", 
							"Manage orderReceipts");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"orderReceipt", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage orderReceipts", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new orderReceiptTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new orderReceiptForm("orderReceiptForm", "adminForm", "orderReceipt");
		$this->addChild($form); 

	}
}


// Define a table component.
class orderReceiptTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("orderReceipt", "id", "orderReceipts");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","orderReceipt Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("orderReceipt_id","orderReceipt Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("orderKey","Order Key");
		$this->addColumn("receiptNo","Receipt#");
		$this->addColumn("customerNo","Customer#");
		$this->addColumn("ordertotal","Order Total");
		$this->addColumn("orderPaid","Order Paid");
		$this->addColumn("surcharges","Surcharges");
		$this->addColumn("srchgPaid","Srchg Paid");
		$this->addColumn("unapplied","Unapplied");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User#");
		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class orderReceiptForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"orderReceipt Id", "");
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
		$this->addField("text", "receiptNo", "Receipt Number");
		$this->addField("text", "customerNo", "Customer Number");
		$this->addField("text", "ordertotal", "Order Total");
		$this->addField("text", "orderPaid", "Order Paid");
		$this->addField("text", "surcharges", "Surcharges");
		$this->addField("text", "srchgPaid", "Surcharge Paid");
		$this->addField("text", "unapplied", "Unapplied");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User Number");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}

