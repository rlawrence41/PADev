<?php

/*
 *	receiptDetail.class.php - These classes are specific to the receiptDetail resource.
 */

// Define a page component.
class receiptDetailPage extends page {
	
	public function __construct() {
		
		parent::__construct("receiptDetail", 
							"receiptDetailPage", 
							"Manage receiptDetails");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"receiptDetail", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage receiptDetails", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new receiptDetailTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new receiptDetailForm("receiptDetailForm", "adminForm", "receiptDetail");
		$this->addChild($form); 

	}
}


// Define a table component.
class receiptDetailTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("receiptDetail", "id", "receiptDetails");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","receiptDetail Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("receiptDetail_id","receiptDetail Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("customerNo","CustomerNo");
		$this->addColumn("customerNoSearch","CustomerNoSearch");
		$this->addColumn("recptDate","RecptDate");
		$this->addColumn("orderKey","OrderKey");
		$this->addColumn("invoiceStr","InvoiceStr");
		$this->addColumn("amount","Amount");
		$this->addColumn("lFullPaymnt","LFullPaymnt");
		$this->addColumn("recptType","RecptType");
		$this->addColumn("crcrdAcct","CrcrdAcct");
		$this->addColumn("crcrdExpDt","CrcrdExpDt");
		$this->addColumn("crcrdVCode","CrcrdVCode");
		$this->addColumn("crcrdAuth","CrcrdAuth");
		$this->addColumn("transactId","TransactId");
		$this->addColumn("lItemized","LItemized");
		$this->addColumn("lRefund","LRefund");
		$this->addColumn("lProcessed","LProcessed");
		$this->addColumn("lExported","LExported");
		$this->addColumn("comment","Comment");
		$this->addColumn("updatedBy","UpdatedBy");
		$this->addColumn("userNo","UserNo");
		$this->addColumn("lastUpdated","LastUpdated");
		$this->addColumn("itemRcptId","ItemRcptId");
		$this->addColumn("itemNo","ItemNo");
		$this->addColumn("titleId","TitleId");
		$this->addColumn("orderQty","OrderQty");
		$this->addColumn("effPrice","EffPrice");
		$this->addColumn("remainQty","RemainQty");
		$this->addColumn("expAmount","ExpAmount");
		$this->addColumn("applyQty","ApplyQty");
		$this->addColumn("appliedAmt","AppliedAmt");


	}
}


// Define a form component.
class receiptDetailForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"receiptDetail Id", "");
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
		$this->addField("text", "id", "Id");
		$this->addField("text", "customerNo", "CustomerNo");
		$this->addField("text", "customerNoSearch", "CustomerNoSearch");
		$this->addField("text", "recptDate", "RecptDate");
		$this->addField("text", "orderKey", "OrderKey");
		$this->addField("text", "invoiceStr", "InvoiceStr");
		$this->addField("text", "amount", "Amount");
		$this->addField("text", "lFullPaymnt", "LFullPaymnt");
		$this->addField("text", "recptType", "RecptType");
		$this->addField("text", "crcrdAcct", "CrcrdAcct");
		$this->addField("text", "crcrdExpDt", "CrcrdExpDt");
		$this->addField("text", "crcrdVCode", "CrcrdVCode");
		$this->addField("text", "crcrdAuth", "CrcrdAuth");
		$this->addField("text", "transactId", "TransactId");
		$this->addField("text", "lItemized", "LItemized");
		$this->addField("text", "lRefund", "LRefund");
		$this->addField("text", "lProcessed", "LProcessed");
		$this->addField("text", "lExported", "LExported");
		$this->addField("text", "comment", "Comment");
		$this->addField("text", "updatedBy", "UpdatedBy");
		$this->addField("text", "userNo", "UserNo");
		$this->addField("text", "lastUpdated", "LastUpdated");
		$this->addField("text", "itemRcptId", "ItemRcptId");
		$this->addField("text", "itemNo", "ItemNo");
		$this->addField("text", "titleId", "TitleId");
		$this->addField("text", "orderQty", "OrderQty");
		$this->addField("text", "effPrice", "EffPrice");
		$this->addField("text", "remainQty", "RemainQty");
		$this->addField("text", "expAmount", "ExpAmount");
		$this->addField("text", "applyQty", "ApplyQty");
		$this->addField("text", "appliedAmt", "AppliedAmt");


	}

}


