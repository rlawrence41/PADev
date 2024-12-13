<?php

/*
 *	appliedOrder.class.php - These classes are specific to the appliedOrder resource.
 */

// Define a page component.
class appliedOrderPage extends page {
	
	public function __construct() {
		
		parent::__construct("appliedOrder", 
							"appliedOrderPage", 
							"Manage appliedOrders");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"appliedOrder", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage appliedOrders", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new appliedOrderTable();
		$table->context = $context;
		$this->addChild($table);
		
		$form = new appliedOrderForm("appliedOrderForm", "adminForm", "appliedOrder");
		$this->addChild($form); 

		// Add transaction specific script(s)...
		$this->scripts[] = "ui/js/appliedOrder.js";

	}
}


// Define a table component.
class appliedOrderTable extends table {
	
	public $applyCheckBox = true;	 // Add the applied check box column.
	public $applyColumn = "applied"; // If set, then the order has already been applied.
	public $applyAction = "showCheck(this)";
	public $editButton = false;			 // Turn edit actions off.
	public $deleteButton = false;
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("appliedOrder", "id", "appliedOrders");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","appliedOrder Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("appliedOrder_id","appliedOrder Id", "A short, memorable name for the account");
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
		$this->addColumn("orderKey","OrderKey");
		$this->addColumn("orderStr","Order #");
		$this->addColumn("orderType","Order Type");
		$this->addColumn("orderDate","Order Date");
#		$this->addColumn("customerNo","CustomerNo");
		$this->addColumn("customerIdSearch","Customer");
		$this->addColumn("orderTotal","Order Total");
		$this->addColumn("orderPaid","Order Paid");
		$this->addColumn("orderBalance","Order Balance");
		$this->addColumn("surcharges","Surcharges");
		$this->addColumn("srchgPaid","Srchg Paid");
#		$this->addColumn("srchgBalance","Srchg Balance");

	}
}


// Define a form component.
class appliedOrderForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"appliedOrder Id", "");
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
		$this->addField("text", "applied", "Applied");
		$this->addField("readonly", "id", "Id");
		$this->addField("text", "orderKey", "OrderKey");
		$this->addField("text", "orderStr", "Order #");
		$this->addField("text", "orderType", "OrderType");
		$this->addField("text", "orderDate", "OrderDate");
		$this->addField("text", "customerNo", "CustomerNo");
		$this->addField("text", "customerIdSearch", "CustomerIdSearch");
		$this->addField("text", "orderTotal", "OrderTotal");
		$this->addField("text", "orderPaid", "OrderPaid");
		$this->addField("text", "orderBalance", "OrderBalance");
		$this->addField("text", "surcharges", "Surcharges");
		$this->addField("text", "srchgPaid", "SrchgPaid");
		$this->addField("text", "srchgBalance", "SrchgBalance");


	}

}
