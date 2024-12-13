<?php

/*
 *	inventory.class.php - These classes are specific to the inventory resource.
 */

// Define a page component.
class inventoryPage extends page {
	
	public function __construct() {
		
		parent::__construct("inventory", 
							"inventoryPage", 
							"Manage Inventory Transactions");
		
		// Add the orderItem script.
		$this->scripts[] = "ui/js/inventory.js";

		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"inventory", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage Inventory Transactions", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new inventoryTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new inventoryForm("inventoryForm", "adminForm", "inventory");
		$this->addChild($form); 

	}
}


// Define a table component.
class inventoryTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("inventory", "id", "inventorys");

		// Add the orderItem script.
		$this->scripts[] = "ui/js/inventory.js";

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","inventory Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("inventory_id","inventory Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("transDate","Trans. Date");
		$this->addColumn("itemNo","Item#");
#		$this->addColumn("titleNo","Title#");
		$this->addColumn("titleId","Title Id");
		$this->addColumn("itemCondtn","Condition", "blank=good, 'D'=Damaged, 'U'=Unusable");
		$this->addColumn("invState","Inventory State", "'I'=in stock, 'P'=purchased, 'R'=released");
#		$this->addColumn("location","Location");
		$this->addColumn("locationSearch","Location");
		$this->addColumn("shipmentNo","Shipment#");
		$this->addColumn("receiptNo","Receipt#");
		$this->addColumn("itemReceiptNo","Item Receipt#");
		$this->addColumn("quantity","Quantity");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User No.");
#		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class inventoryForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"inventory Id", "");
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
		$this->addField("text", "transDate", "TransDate");
		$this->addField("text", "itemNo", "Item#");
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


		$this->addField("text", "titleId", "Title Id");
		$this->addField("text", "itemCondtn", "Condition", "blank=good, 'D'=Damaged, 'U'=Unusable");
		$itemCondtnField = $this->addField("select", "itemCondtn", "Item Condition");
			// Assign a list of options and define which should be selected.
			$itemCondtnField->options = [" ", "D", "U"];
			$itemCondtnField->addOptions(" ");
		
		$invStateField = $this->addField("select", "invState", "InvState", "'I'=in stock, 'P'=purchased, 'R'=released");
			// Assign a list of options and define which should be selected.
			$invStateField->options = [" ", "I", "P", "R"];
			$invStateField->addOptions("I");

		$this->addField("hidden", "location", "Location");

		// The searchField component needs more explanation...
		$searchField = new searchField("location", 					// Name of the field to be saved in this instance
					"Warehouse Location", 							// Label for the search field (appears in the form)
					"Enter an ID, company, last name, or email",	// Description (used as a placeholder)
					"contact");										// Resource to search

		$this->addChild($searchField);

		$this->addField("text", "shipmentNo", "Shipment#");
		$this->addField("text", "receiptNo", "Receipt#");
		$this->addField("text", "itemReceiptNo", "Item Receipt#");
		$this->addField("text", "quantity", "Quantity");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");

	}

}


