<?php

/*
 *	purchaseOrderRpt.class.php - This is the order report for the purchase order
 *	transaction.
 *
 */


// Define a page component.
class purchaseOrderRptPage extends reportPage {
	
	public function __construct() {
		
		parent::__construct("purchaseOrder", 
							"purchaseOrderRptPage", 
							"Purchase Order Report",
							"A purchase order suitable for printing.");
		
		$this->scripts[] = "ui/js/purchaseOrder.js";

		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"purchaseOrder", 
								"id");
		$this->context = $context;			// Rendered separately.
		
		$report = new purchaseOrderReport();
		$report->context = $context;
		$this->addChild($report);

	}
}


// Define a report component.
class purchaseOrderReport extends report {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title, description, action.
		parent::__construct("purchaseOrder",
							"id", 
							"Purchase Order Report", 
							"Purchase Order report suitable for printing and sending to supplier");

		// Knock out the title page for application purposes.
		$title = $this->children['reportTitle'] ;
		$title->template = "";

		// For convenience, capture the major components for the report.
		$group = $this->children['reportGroup'];
		$header = $group->children['groupHeader'];
		$detail = $group->children['groupDetail'];
		$summary = $group->children['groupSummary'];

		// Assign custom templates to the group header and summary bands.
		// These templates will likely be in the template folder for this application.		
		$header->template = "purchaseOrderGroupHeader.html";
		$summary->template = "purchaseOrderGroupSummary.html";

		// Add fields specific to this resource.
		// Keep only the fields that will be useful for the report.
		// Add headers when fields are to be displayed in a table.
		// Otherwise, the field title will be used as a label.
		// If no title is provided, then field content will be displayed
		//	without a label.

		
		// List the fields that define the group.
		// In other words, if the value of these fields changes, then the report 
		// should present a new group.
		$group->addGroupField("orderKey", "Order Key");
		$group->addGroupField("orderNo", "Order Number");
		
		
		// The following fields are presented in the detail table.
#		$group->addDetailField("id", "Item ID");
#		$group->addDetailField("itemId", "Item Id", 'width="10%"');
		$group->addDetailField("titleId", "Title Id", 'width="15%"');
		$group->addDetailField("title", "Title", 'width="25%"');
		$group->addDetailField("itemCondtn", "Cond.", 'width="5%"');
		$group->addDetailField("quantity", "Quantity", 'width="10%"');
		$group->addDetailField("price", "Price", 'width="10%"');
		$group->addDetailField("discount", "Discount", 'width="10%"');
		$group->addDetailField("deduction", "Deduction", 'width="10%"');
		$group->addDetailField("extPrice", "Ext. Price", 'width="15%"');
		
		// A sample detail field with vertical column heading...
#		$group->addDetailField("2022GEParticipation", "Voted 2022?", 'class="verticalCH" width="6%"');

 
		// Summarize the following fields for the summary band.
#		$group->addSummaryField("extPrice", "Subtotal");
#		$group->addSummaryField("extWeight", "Shipping Weight");
 
	}

}	

/*		// The following may be in the report but there is no need to declare.
		$group->addDetailField("id","Id");
		$group->addDetailField("titleNo","TitleNo");
		$group->addDetailField("lInventory","LInventory");
		$group->addDetailField("lConsignment","LConsignment");
		$group->addDetailField("orderDate","OrderDate");
		$group->addDetailField("shipWeight","ShipWeight");
		$group->addDetailField("itemStatus","ItemStatus");
		$group->addDetailField("lSubscript","LSubscript");
		$group->addDetailField("expireDate","ExpireDate");
		$group->addDetailField("comment","Comment");
		$group->addDetailField("updatedBy","Updated By");
		$group->addDetailField("userNo","User No.");
		$group->addDetailField("lastUpdated","Last Updated");
 */	

	



