<?php

/*
 *	customerOrder.class.php - These classes are specific to the customerOrder resource.
 */

// Define a page component.
class customerOrderPage extends page {
	
	public function __construct() {
		
		parent::__construct("customerOrder", 
							"customerOrderPage", 
							"Manage Customer Orders");

		// Add customer order javascript.
		$this->scripts[] = "ui/js/customerOrder.js";
							
		// Add the wizard script to the list of scripts.
		$this->scripts[] = "/common/ui/js/wizard.js";
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"customerOrder", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage Customer Orders", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;						// Make context available to navigation.
		$pageNav->setAddAction('onclick="addParent()"');	// The Add button should add a new customer order.
		$this->addChild($pageNav);

		$table = new customerOrderTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new customerOrderForm("customerOrderForm", "adminForm", "customerOrder");
		$this->addChild($form); 

	}

}


// Define a table component.
class customerOrderTable extends table {
	
	// This is a view.  Let's turn actions off and selection on.
	public $editButton = false ;		// Turn the edit button OFF and 
	public $deleteButton = true ;		// Allow the entire transaction to be deleted. 
	public $selectButton = true ;		// turn the selection option ON for the table.
	
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("customerOrder", "id", "Customer Orders");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","customerOrder Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("customerOrder_id","customerOrder Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
#		$this->addColumn("id","Id");
#		$this->addColumn("orderNo","OrderNo");
#		$this->addColumn("invoiceNo","InvoiceNo");
		$this->addColumn("invoiceStr","InvoiceStr");
#		$this->addColumn("orderType","OrderType");
#		$this->addColumn("orderDate","OrderDate");
#		$this->addColumn("custPONo","CustPONo");
#		$this->addColumn("supplierNo","SupplierNo");
#		$this->addColumn("supplierIdSearch","SupplierIdSearch");
#		$this->addColumn("customerNo","CustomerNo");
		$this->addColumn("customerIdSearch","Customer");
#		$this->addColumn("shipToNo","ShipToNo");
		$this->addColumn("shipToIdSearch","Ship To");
#		$this->addColumn("shipToAddr","ShipToAddr");
#		$this->addColumn("lCreditCard","LCreditCard");
#		$this->addColumn("courier","Courier");
#		$this->addColumn("orderWeight","OrderWeight");
#		$this->addColumn("shipCharge","ShipCharge");
		$this->addColumn("terms","Terms");
#		$this->addColumn("termsDesc","TermsDesc");
#		$this->addColumn("salesRepNo","SalesRepNo");
#		$this->addColumn("salesRepIdSearch","SalesRepIdSearch");
#		$this->addColumn("orderDiscount","OrderDiscount");
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
		$this->addColumn("shipDate","Ship Date");
#		$this->addColumn("batchNo","BatchNo");
#		$this->addColumn("lProcessed","LProcessed");
#		$this->addColumn("comment","Comment");
#		$this->addColumn("lExported","LExported");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User No.");
#		$this->addColumn("lastUpdated","Last Updated");
#		$this->addColumn("itemId","ItemId");
#		$this->addColumn("orderKey","OrderKey");
#		$this->addColumn("titleNo","TitleNo");
		$this->addColumn("titleId","Title Id");
		$this->addColumn("title","Title");
#		$this->addColumn("lInventory","LInventory");
#		$this->addColumn("lConsignment","LConsignment");
#		$this->addColumn("itemCondtn","ItemCondtn");
		$this->addColumn("quantity","Quantity");
#		$this->addColumn("itemDate","ItemDate");
#		$this->addColumn("price","Price");
#		$this->addColumn("discount","Discount");
#		$this->addColumn("deduction","Deduction");
#		$this->addColumn("shipWeight","ShipWeight");
#		$this->addColumn("itemStatus","ItemStatus");
#		$this->addColumn("lSubscript","LSubscript");
#		$this->addColumn("expireDate","ExpireDate");
		$this->addColumn("extPrice","Ext. Price");
#		$this->addColumn("extWeight","ExtWeight");
#		$this->addColumn("itemComment","ItemComment");

	}
}


// Define a form component.
class customerOrderForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"customerOrder Id", "");
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
		$this->addField("text", "orderNo", "OrderNo");
		$this->addField("text", "invoiceNo", "InvoiceNo");
		$this->addField("text", "invoiceStr", "InvoiceStr");
		$this->addField("text", "orderType", "OrderType");
		$this->addField("text", "orderDate", "OrderDate");
		$this->addField("text", "custPONo", "CustPONo");
		$this->addField("text", "supplierNo", "SupplierNo");
		$this->addField("text", "supplierIdSearch", "SupplierIdSearch");
		$this->addField("text", "customerNo", "CustomerNo");
		$this->addField("text", "customerIdSearch", "CustomerIdSearch");
		$this->addField("text", "shipToNo", "ShipToNo");
		$this->addField("text", "shipToIdSearch", "ShipToIdSearch");
		$this->addField("text", "shipToAddr", "ShipToAddr");
		$this->addField("text", "lCreditCard", "LCreditCard");
		$this->addField("text", "courier", "Courier");
		$this->addField("text", "orderWeight", "OrderWeight");
		$this->addField("text", "shipCharge", "ShipCharge");
		$this->addField("text", "terms", "Terms");
		$this->addField("text", "termsDesc", "TermsDesc");
		$this->addField("text", "salesRepNo", "SalesRepNo");
		$this->addField("text", "salesRepIdSearch", "SalesRepIdSearch");
		$this->addField("text", "orderDiscount", "OrderDiscount");
		$this->addField("text", "lTaxable", "LTaxable");
		$this->addField("text", "stateTaxRate", "stateTaxRate");
		$this->addField("text", "lTaxShip", "LTaxShip");
		$this->addField("text", "localTaxRate", "localTaxRate");
		$this->addField("text", "adjType1", "AdjType1");
		$this->addField("text", "adjustment1", "Adjustment1");
		$this->addField("text", "adjType2", "AdjType2");
		$this->addField("text", "adjustment2", "Adjustment2");
		$this->addField("readonly", "subtotal", "Subtotal");
		$this->addField("readonly", "noTaxSubtotal", "No Tax Subtotal");
		$this->addField("readonly", "stateTax", "State Tax");
		$this->addField("readOnly", "localTax", "Local Tax");
		$this->addField("readonly", "total", "Total");
		$this->addField("text", "status", "Status");
		$this->addField("text", "priorState", "PriorState");
		$this->addField("text", "shipDate", "ShipDate");
		$this->addField("text", "batchNo", "BatchNo");
		$this->addField("text", "lProcessed", "LProcessed");
		$this->addField("text", "comment", "Comment");
		$this->addField("text", "lExported", "LExported");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");
		$this->addField("text", "itemId", "ItemId");
		$this->addField("text", "orderKey", "OrderKey");
		$this->addField("text", "titleNo", "TitleNo");
		$this->addField("text", "titleId", "TitleId");
		$this->addField("text", "title", "Title");
		$this->addField("text", "lInventory", "LInventory");
		$this->addField("text", "lConsignment", "LConsignment");
		$this->addField("text", "itemCondtn", "ItemCondtn");
		$this->addField("text", "quantity", "Quantity");
		$this->addField("text", "itemDate", "ItemDate");
		$this->addField("text", "price", "Price");
		$this->addField("text", "discount", "Discount");
		$this->addField("text", "deduction", "Deduction");
		$this->addField("text", "shipWeight", "ShipWeight");
		$this->addField("text", "itemStatus", "ItemStatus");
		$this->addField("text", "lSubscript", "LSubscript");
		$this->addField("text", "expireDate", "ExpireDate");
		$this->addField("text", "extPrice", "ExtPrice");
		$this->addField("text", "extWeight", "ExtWeight");
		$this->addField("text", "itemComment", "ItemComment");


	}

}


