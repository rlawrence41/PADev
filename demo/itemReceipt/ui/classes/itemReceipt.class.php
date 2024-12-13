<?php

/*
 *	itemReceipt.class.php - These classes are specific to the itemReceipt resource.
 */

// Define a page component.
class itemReceiptPage extends page {
	
	public function __construct() {
		
		parent::__construct("itemReceipt", 
							"itemReceiptPage", 
							"Manage itemReceipts");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"itemReceipt", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage itemReceipts", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new itemReceiptTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new itemReceiptForm("itemReceiptForm", "adminForm", "itemReceipt");
		$this->addChild($form); 

	}
}


// Define a table component.
class itemReceiptTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("itemReceipt", "id", "itemReceipts");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","itemReceipt Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("itemReceipt_id","itemReceipt Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("itemNo","Item#");
		$this->addColumn("titleNo","Title#");
		$this->addColumn("titleId","Title Id");
		$this->addColumn("orderKey","Order Key");
		$this->addColumn("customerNo","Customer#");
		$this->addColumn("receiptNo","Receipt#");
		$this->addColumn("remainQty","Remain Qty");
		$this->addColumn("applyQty","Apply Qty");
		$this->addColumn("amount","Amount");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User#");
		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class itemReceiptForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"itemReceipt Id", "");
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
		$this->addField("text", "itemNo", "Item Number");
		$this->addField("text", "titleNo", "Title Number");
		$this->addField("text", "titleId", "Title Id");
		$this->addField("text", "orderKey", "Order Key");
		$this->addField("text", "customerNo", "Customer Number");
		$this->addField("text", "receiptNo", "Receipt Number");
		$this->addField("text", "remainQty", "Remain Quantity");
		$this->addField("text", "applyQty", "Apply Quantity");
		$this->addField("text", "amount", "Amount");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User Number");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


