<?php

/*
 *	appliedItem.class.php - These classes are specific to the appliedItem resource.
 */

// Define a page component.
class appliedItemPage extends page {
	
	public function __construct() {
		
		parent::__construct("appliedItem", 
							"appliedItemPage", 
							"Manage appliedItems");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"appliedItem", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage appliedItems", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new appliedItemTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new appliedItemForm("appliedItemForm", "adminForm", "appliedItem");
		$this->addChild($form); 

		// Add transaction specific script(s)...
		$this->scripts[] = "ui/js/appliedItem.js";

	}
}


// Define a table component.
class appliedItemTable extends table {

	public $applyCheckBox = true;	 		// Add the applied check box column.
	public $applyColumn = "applied"; 		// If set, then funds have already been applied.
	public $applyAction = "showCheck(this)";
	public $editButton = true;			 	// Leave edit action on, but
	public $deleteButton = false;			// turn delete action off.

// This application uniquely works with the appliedItem resource, but saves to
// the itemReceipt resource.  Reset the edit action to force this deviation from
// the standard routines in pageAction.js.

	public $editAction = 'onclick="getItemReceipt({keyValue})"';
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("appliedItem", "id", "appliedItems");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","appliedItem Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("appliedItem_id","appliedItem Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
#		$this->addColumn("applied","Applied");
#		$this->addColumn("id","Id");
		$this->addColumn("itemNo","Item Id");
		$this->addColumn("titleNo","Title No");
		$this->addColumn("titleId","Title Id");
		$this->addColumn("orderKey","Order Key");
		$this->addColumn("orderType","Order Type");
		$this->addColumn("orderStr","Order Str");
		$this->addColumn("customerNo","Customer No");
		$this->addColumn("customerIdSearch","Customer");
		$this->addColumn("receiptNo","Receipt No");
		$this->addColumn("orderQty","Order Qty");
		$this->addColumn("remainQty","Remain Qty");
		$this->addColumn("effPrice","Effective Price");
		$this->addColumn("expAmount","Expected Amount");
		$this->addColumn("applyQty","Apply Qty");
		$this->addColumn("amount","Amount Applied");


	}
}


// Define a form component.
class appliedItemForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"appliedItem Id", "");
		$fieldGroup->addField("text", 	"company", 		"Company", "Company or organization name");
		$fieldGroup->addField("text", 	"first_name", 	"First Name", "first name");
		$fieldGroup->addField("text", 	"last_name", 	"Last Name", "last name");

		// Sample Standard fields... 
		$this->addField("textarea", 	"po_addr", 		"Street", "Postal Service Street Address ");
		$this->addField("text", 		"city", 		"City", "City or town");
		$this->addField("text", 		"state_abbr", 	"State", "State Abbreviation");
		$this->addField("text", 		"zip_code", 	"Zip Code", "Postal Code");
		*/

/* 
		Replacing the appliedItem fields with itemReceipt form fields...

		// Fields unique to this resource...
#		$this->addField("readonly", "applied", "Applied");
		$this->addField("hidden", "id", "Id");
		$this->addField("readonly", "itemNo", "ItemNo");
		$this->addField("readonly", "titleId", "TitleId");
		$this->addField("readonly", "orderKey", "OrderKey");
#		$this->addField("readonly", "orderType", "OrderType");
#		$this->addField("readonly", "orderStr", "OrderStr");
		$this->addField("readonly", "customerNo", "CustomerNo");
		$this->addField("readonly", "customerIdSearch", "CustomerIdSearch");
		$this->addField("readonly", "receiptNo", "ReceiptNo");
		$this->addField("readonly", "orderQty", "OrderQty");
		$this->addField("readonly", "remainQty", "RemainQty");
		$this->addField("readonly", "effPrice", "EffPrice");
		$this->addField("readonly", "expAmount", "ExpAmount");
		$this->addField("text", "applyQty", "ApplyQty");
		$this->addAction("applyQty", 'onchange="updateAmount()"');
		$this->addField("text", "amount", "Amount");

		// Remove foreign fields from the data-set class for updating.
#		$this->children['applied']->doNotSave();
#		$this->children['orderType']->doNotSave();
#		$this->children['orderStr']->doNotSave();
		$this->children['customerIdSearch']->doNotSave();
		$this->children['orderQty']->doNotSave();
		$this->children['effPrice']->doNotSave();
		$this->children['expAmount']->doNotSave();
 */
 
 
 		// Fields unique to this resource...
		$this->addField("hidden", "id", "Id");
		$this->addField("readonly", "itemNo", "Item No.");
		$this->addField("readonly", "titleId", "Title Id");
		$this->addField("readonly", "orderKey", "Order Key");
		$this->addField("readonly", "customerNo", "Customer No.");
		$this->addField("readonly", "receiptNo", "Receipt No.");
		$this->addField("readonly", "remainQty", "Remaining Quantity");
		$this->addField("readonly", "effPrice", "Effective Price");
		$this->children['effPrice']->doNotSave();
		$this->addField("readonly", "expAmount", "Expected Amount");
		$this->children['expAmount']->doNotSave();
		$this->addField("text", "applyQty", "ApplyQty");
		$this->addAction("applyQty", 'onchange="updateAmount()"');
		$this->addField("text", "amount", "Amount");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");

 
 
 
	}

}


