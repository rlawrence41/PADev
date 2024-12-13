<?php

/*
 *	orders.class.php - These classes are specific to the orders resource.
 */

// Define a page component.
class ordersPage extends page {
	
	public function __construct() {
		
		parent::__construct("orders", 
							"ordersPage", 
							"Manage Orders");
		
		// Add a resource specific script.
		$this->scripts[] = "ui/js/orders.js";
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"orders", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage Orders", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new ordersTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new ordersForm("ordersForm", "adminForm", "orders");
		$this->addChild($form); 

	}
}


// Define a table component.
class ordersTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("orders", "id", "Orders");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","orders Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("orders_id","orders Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
#		$this->addColumn("orderNo","Order#");
#		$this->addColumn("invoiceNo","Invoice#");
		$this->addColumn("invoiceStr", 'Order/ Invoice #');
		$this->addColumn("orderType","Order Type");
		$this->addColumn("orderDate","Order Date");
#		$this->addColumn("custPONo","CustPO#");
#		$this->addColumn("supplierNo","Supplier#");
#		$this->addColumn("customerNo","Customer#");
		$this->addColumn("customerNoSearch","Customer");
#		$this->addColumn("shipToNo","ShipTo#");
		$this->addColumn("shipToNoSearch","Ship To");
#		$this->addColumn("shipToAddr","shipToAddr");
#		$this->addColumn("lCreditCard","LCreditCard");
		$this->addColumn("courier","Courier");
#		$this->addColumn("courierSearch","Courier");
#		$this->addColumn("shipWeight","ShipWeight");
#		$this->addColumn("shipCharge","ShipCharge");
		$this->addColumn("terms","Terms");
#		$this->addColumn("termsDesc","TermsDesc");
#		$this->addColumn("salesRepNo","SalesRep#");
#		$this->addColumn("salesRepNoSearch","Sales Rep");
#		$this->addColumn("discount","Discount");
#		$this->addColumn("lTaxable","LTaxable");
#		$this->addColumn("stateTaxRate","stateTaxRate");
#		$this->addColumn("lTaxShip","LTaxShip");
#		$this->addColumn("localTaxRate","localTaxRate");
#		$this->addColumn("adjType1","AdjType1");
#		$this->addColumn("adjustment1","Adjustment1");
#		$this->addColumn("adjType2","AdjType2");
#		$this->addColumn("adjustment2","Adjustment2");
#		$this->addColumn("subtotal","Subtotal");
		$this->addColumn("total","Total");
		$this->addColumn("status","Status");
#		$this->addColumn("priorState","PriorState");
#		$this->addColumn("shipDate","ShipDate");
		$this->addColumn("batchNo","Batch#");
#		$this->addColumn("lProcessed","LProcessed");
#		$this->addColumn("comment","Comment");
#		$this->addColumn("lExported","LExported");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User#");
		$this->addColumn("lastUpdated","Last Updated");

	}
}


// Define a form component.
class ordersForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"orders Id", "");
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
		$this->addField("text", "orderNo", "Order Number");
		$this->addField("text", "invoiceNo", "Invoice Number");
		
		// The order type will determine which status option is available, "Received"
		//	or "Shipped".  However, this application doesn't know what the orderType
		//	is until an order record is loaded.  So, I've added an onChange() event
		//	handler here to check the orderType when the order data is loaded into 
		//	the form.  This happens now in function loadFormFromJSON() in 
		//	pageActions.js.  The option is reset via a javascript function in orders.js.
		$orderTypeField = $this->addField("text", "orderType", "Order Type");
		$orderTypeField->action = "onChange='changeStatusOption(this)'";
		
		
		$this->addField("text", "orderDate", "Order Date");
		$this->addField("text", "custPONo", "Cust. PO Number");
		$this->addField("hidden", "supplierNo", "Supplier Number");

		// The searchField component needs more explanation...
		$searchField = new searchField("supplierNo", 				// Name of the field to be saved in this instance
					"Supplier", 									// Label for the search field (appears in the form)
					"Enter an ID, company, last name, or email",	// Description (used as a placeholder)
					"contact");										// Resource to search

		$this->addChild($searchField);

		$this->addField("hidden", "customerNo", "Customer Number");

		// The searchField component needs more explanation...
		$searchField = new searchField("customerNo", 				// Name of the field to be saved in this instance
					"Customer", 									// Label for the search field (appears in the form)
					"Enter an ID, company, last name, or email",	// Description (used as a placeholder)
					"contact");										// Resource to search

		$this->addChild($searchField);

		$this->addField("hidden", "shipToNo", "Ship To Number");

		// The searchField component needs more explanation...
		$searchField = new searchField("shipToNo", 					// Name of the field to be saved in this instance
					"Ship To", 										// Label for the search field (appears in the form)
					"Enter an ID, company, last name, or email",	// Description (used as a placeholder)
					"contact");										// Resource to search

		$this->addChild($searchField);

		$this->addField("textarea", "shipToAddr", "Ship To Addr");
		$this->addField("text", "lCreditCard", "LCreditCard");

		$this->addField("readonly", "courier", "Courier");
		// The searchField component needs more explanation...
		$searchField = new searchField("courier",	 				// Name of the field to be saved in this instance
					"Ship Via", 									// Label for the search field (appears in the form)
					"Enter a courier service name",					// Description (used as a placeholder)
					"courier");										// Resource to search

		$this->addChild($searchField);

		$this->addField("text", "shipWeight", "Ship Weight");
		$this->addField("text", "shipCharge", "Ship Charge");
#		$this->addField("text", "terms", "Terms");
		$termsField = $this->addField("select", "terms", "Terms");
			// Assign a list of options and define which should be selected.
			$termsField->options = ["Prepaid",
									"Consignment",
									"Net 30 Days",
									"Net 60 Days",
									"Net 90 Days",
									"Net 120 Days",
									"No Royalty",
									"Other",
									"Transfer",
									"Upon Receipt"
									];
			$termsField->addOptions("Prepaid");

		$this->addField("text", "termsDesc", "Terms Desc");
		$this->addField("readonly", "salesRepNo", "Sales Rep Number");

		// The searchField component needs more explanation...
		$searchField = new searchField("salesRepNo",				// Name of the field to be saved in this instance
					"Sales Representative", 						// Label for the search field (appears in the form)
					"Enter an ID, company, last name, or email",	// Description (used as a placeholder)
					"contact");										// Resource to search

		$this->addChild($searchField);

		$this->addField("text", "discount", "Discount");
		$this->addField("checkbox", "lTaxable", "Taxable?");
		$this->addField("text", "stateTaxRate", "State Tax Rate");
		$this->addField("checkbox", "lTaxShip", "Tax Shipping?");
		$this->addField("text", "localTaxRate", "Local Tax Rate");
		$this->addField("text", "adjType1", "Adjustment1 Description");
		$this->addField("text", "adjustment1", "Adjustment1 Amount");
		$this->addField("text", "adjType2", "Adjustment2 Description");
		$this->addField("text", "adjustment2", "Adjustment2 Amount");
		$this->addField("readonly", "subtotal", "Subtotal");
		$this->addField("readonly", "noTaxSubtotal", "No Tax Subtotal");
		$this->addField("readonly", "stateTax", "State Tax");
		$this->addField("readOnly", "localTax", "Local Tax");
		$this->addField("readonly", "total", "Total");

#		$this->addField("text", "status", "Status");
		$statusField = $this->addField("select", "status", "Status");

			$statusField->options = ["Open",
									"Received",
									"Paid",
									"Closed",
									"Deleted"];

			// Load the list of options and default to "open"
			$statusField->addOptions("Open");

		$this->addField("text", "priorState", "Prior State");
		$this->addField("text", "shipDate", "Ship Date");
		$this->addField("text", "batchNo", "Print Batch Number");
		$this->addField("checkbox", "lProcessed", "Processed?");
		$this->addField("textarea", "comment", "Comment");
		$this->addField("checkbox", "lExported", "Exported?");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User#");
		$this->addField("readonly", "lastUpdated", "Last Updated");

	}
}


