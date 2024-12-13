<?php

/*
 *	customerReturn.class.php - These classes are specific to the customerReturn resource.
 */

// Define a page component.
class customerReturnPage extends page {
	
	public function __construct() {
		
		parent::__construct("customerReturn", 
							"customerReturnPage", 
							"Manage Customer Returns");

		// Add customer return javascript.
		$this->scripts[] = "ui/js/customerReturn.js";
							
		// Add the wizard script to the list of scripts.
		$this->scripts[] = "/common/ui/js/wizard.js";
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"customerReturn", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage Customer Returns", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;						// Make context available to navigation.
		$pageNav->setAddAction('onclick="addParent()"');	// The Add button should add a new customer return.
		$this->addChild($pageNav);

		$table = new customerReturnTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new customerReturnForm("customerReturnForm", "adminForm", "customerReturn");
		$this->addChild($form); 

	}

}


// Define a table component.
class customerReturnTable extends table {
	
	// This is a view.  Let's turn actions off and selection on.
	public $editButton = false ;		// Turn the edit button OFF and 
	public $deleteButton = true ;		// Turn the delete button OFF and 
	public $selectButton = true ;		// turn the selection option ON for the table.
	
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("customerReturn", "id", "Customer Returns");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","customerReturn Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("customerReturn_id","customerReturn Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
#		$this->addColumn("id","Id");
		$this->addColumn("orderNo","OrderNo");
#		$this->addColumn("invoiceNo","InvoiceNo");
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
class customerReturnForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"customerReturn Id", "");
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
#		$this->addField("text", "invoiceNo", "InvoiceNo");
		$this->addField("text", "orderType", "Order Type");
		$this->addField("text", "orderDate", "Return Date");
		$this->addField("text", "custPONo", "CustPONo");
		$this->addField("text", "supplierNo", "Supplier No");
		$this->addField("text", "supplierIdSearch", "SupplierId Search");
		$this->addField("text", "customerNo", "CustomerNo");
		$this->addField("text", "customerIdSearch", "CustomerIdSearch");
		$this->addField("text", "shipToNo", "ShipTo No");
		$this->addField("text", "shipToIdSearch", "ShipToId Search");
		$this->addField("text", "shipToAddr", "ShipTo Addr");
		$this->addField("text", "lCreditCard", "CreditCard?");
		$this->addField("text", "courier", "Courier");
		$this->addField("text", "orderWeight", "Order Weight");
		$this->addField("text", "shipCharge", "Ship Charge");
		$this->addField("text", "terms", "Terms");
		$this->addField("text", "termsDesc", "Terms Desc.");
		$this->addField("text", "salesRepNo", "SalesRep No");
		$this->addField("text", "salesRepIdSearch", "SalesRepId Search");
		$this->addField("text", "orderDiscount", "Order Discount");
		$this->addField("text", "lTaxable", "Taxable?");
		$this->addField("text", "stateTaxRate", "State Tax Rate");
		$this->addField("text", "lTaxShip", "Tax Shipping?");
		$this->addField("text", "localTaxRate", "Local Tax Rate");
		$this->addField("text", "adjType1", "Adj Type1");
		$this->addField("text", "adjustment1", "Adjustment1");
		$this->addField("text", "adjType2", "Adj Type2");
		$this->addField("text", "adjustment2", "Adjustment2");
		$this->addField("readonly", "subtotal", "Subtotal");
		$this->addField("readonly", "noTaxSubtotal", "No Tax Subtotal");
		$this->addField("readonly", "stateTax", "State Tax");
		$this->addField("readOnly", "localTax", "Local Tax");
		$this->addField("readonly", "total", "Total");
		$this->addField("text", "status", "Status");
		$this->addField("text", "priorState", "Prior State");
		$this->addField("text", "shipDate", "Received Date");
		$this->addField("text", "batchNo", "Batch No");
		$this->addField("text", "lProcessed", "Processed?");
		$this->addField("text", "comment", "Comment");
		$this->addField("text", "lExported", "Exported?");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");
		$this->addField("text", "itemId", "ItemId");
		$this->addField("text", "orderKey", "OrderKey");
		$this->addField("text", "titleNo", "Title No");
		$this->addField("text", "titleId", "Title Id");
		$this->addField("text", "title", "Title");
		$this->addField("text", "lInventory", "Inventory?");
		$this->addField("text", "lConsignment", "Consignment?");
		$this->addField("text", "itemCondtn", "Item Condtn");
		$this->addField("text", "quantity", "Quantity");
		$this->addField("text", "itemDate", "Item Date");
		$this->addField("text", "price", "Price");
		$this->addField("text", "discount", "Discount");
		$this->addField("text", "deduction", "Deduction");
		$this->addField("text", "shipWeight", "Ship Weight");
		$this->addField("text", "itemStatus", "Item Status");
		$this->addField("text", "lSubscript", "Subscription?");
		$this->addField("text", "expireDate", "Expire Date");
		$this->addField("text", "extPrice", "Ext. Price");
		$this->addField("text", "extWeight", "Ext. Weight");
		$this->addField("text", "itemComment", "Item Comment");


	}

}


