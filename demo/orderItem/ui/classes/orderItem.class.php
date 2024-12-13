<?php

/*
 *	orderItem.class.php - These classes are specific to the orderItem resource.
 */

// Define a page component.
class orderItemPage extends page {
	
	public function __construct() {
		
		parent::__construct("orderItem", 
							"orderItemPage", 
							"Manage orderItems");
		
		// Add the orderItem script.
		$this->scripts[] = "ui/js/orderItem.js";

		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"orderItem", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage orderItems", 
								"Allows you to navigate to a page within the resource.");

		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new orderItemTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new orderItemForm("orderItemForm", "adminForm", "orderItem");
		$this->addChild($form); 

	}
}


// Define a table component.
class orderItemTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("orderItem", "id", "orderItems");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","orderItem Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("orderItem_id","orderItem Id", "A short, memorable name for the account");
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
		$this->addColumn("titleNo","Title No");
		$this->addColumn("titleId","Title Id");
		$this->addColumn("title","Title");
		$this->addColumn("lInventory","Inv.");
#		$this->addColumn("lConsignment","LConsignment");
		$this->addColumn("itemCondtn","Cond.");
		$this->addColumn("quantity","Quantity");
		$this->addColumn("orderDate","Order Date");
		$this->addColumn("price","Price");
		$this->addColumn("discount","Discount");
#		$this->addColumn("deduction","Deduction");
		$this->addColumn("effPrice","Effective Price");
		$this->addColumn("extPrice","Extended Price");
#		$this->addColumn("shipWeight","ShipWeight");
		$this->addColumn("itemStatus","Item Status");
#		$this->addColumn("lSubscript","LSubscript");
#		$this->addColumn("expireDate","ExpireDate");
#		$this->addColumn("comment","Comment");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User No.");
#		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class orderItemForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"orderItem Id", "");
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
		$this->addField("text", "orderKey", "Order Key");
		$this->addField("readonly", "titleNo", "Title No.");
		
		// Allow a search for a title.
		$searchField = new searchField("titleNo", 	// Foreign key field
					"Select a Title", 				// label
					"Enter a title or title Id.",	// description
					"title");						// target resource
		// Add a change event to the titleNo input element. 
		// TitleNo is the foreign key element embedded in the searchField.
		$searchField->foreignKey->action = "onchange='getTitle(this.value)'";
		$this->addChild($searchField);
		
		$this->addField("readonly", "titleId", "TitleId");
		$this->addField("readonly", "title", "Title");
		$this->addField("checkbox", "lInventory", "Inventory?");
		$this->addField("checkbox", "lConsignment", "Consignment?");
		$this->addField("checkbox", "lTaxable", "Taxable?");
		$this->addField("text", "itemCondtn", "ItemCondtn");
		$this->addField("text", "quantity", "Quantity");
		$this->addField("text", "orderDate", "OrderDate");
		$this->addField("text", "price", "Price");
		$this->addField("text", "discount", "Discount");
		$this->addAction("discount", "onfocusout='updateDeduction()'");
		$this->addField("text", "deduction", "Deduction");
		$this->addAction("deduction", "onfocusout='updateDiscount()'");
		$this->addField("readonly", "effPrice", "Effective Price");
		$this->children['effPrice']->doNotSave();
		$this->addField("readonly", "extPrice", "Extended Price");
		$this->children['extPrice']->doNotSave();
		$this->addField("text", "shipWeight", "ShipWeight");
		$this->addField("text", "itemStatus", "ItemStatus");
		$this->addField("checkbox", "lSubscript", "Subscription?");
		$this->addField("text", "expireDate", "Expire Date");
		$this->addField("textarea", "comment", "Comment");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


