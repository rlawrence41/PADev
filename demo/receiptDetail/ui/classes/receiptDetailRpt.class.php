<?php

/*
 *	receiptDetailRpt.class.php - This is the invoice report for the customer
 *	order transaction.
 *
 */


// Define a page component.
class receiptDetailRptPage extends reportPage {
	
	public function __construct() {
		
		parent::__construct("receiptDetail", 
							"receiptDetailRptPage", 
							"Receipts From Customers",
							"Receipt Details Report");
		
		$this->scripts[] = "/common/ui/js/pageActions.js";
		$this->scripts[] = "ui/js/receiptDetail.js";

		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"receiptDetail", 
								"id");
		$this->context = $context;			// Rendered separately.
		
		$report = new receiptDetailReport();
		$report->context = $context;
		$this->addChild($report);

	}
}


// Define a report component.
class receiptDetailReport extends report {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title, description, action.
		parent::__construct("receiptDetail",
							"id", 
							"Receipts From Customers",
							"Receipt Details Report");

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
		$header->template = "receiptDetailGroupHeader.html";
		$summary->template = "receiptDetailGroupSummary.html";

		// Add fields specific to this resource.
		// Keep only the fields that will be useful for the report.
		// Add headers when fields are to be displayed in a table.
		// Otherwise, the field title will be used as a label.
		// If no title is provided, then field content will be displayed
		//	without a label.

		
		// List the fields that define the group.
		// In other words, if the value of these fields changes, then the report 
		// should present a new group.
		$group->addGroupField("id", "Receipt No.");
		
		
		// The following fields are presented in the detail table.
#		$group->addDetailField("id", "Item ID");
#		$group->addDetailField("itemId", "Item Id", 'width="10%"');
		$group->addDetailField("titleId", "Title Id", 'width="15%"');
		$group->addDetailField("orderStr", "Order #", 'width="10%"');
		$group->addDetailField("orderQty", "Order Qty", 'width="10%"');
		$group->addDetailField("remainQty", "Remaining Qty", 'width="10%"');
		$group->addDetailField("expAmount", "Expected Amount", 'width="10%"');
		$group->addDetailField("applyQty", "Applied To Qty", 'width="10%"');
		$group->addDetailField("appliedAmt", "Applied Amount", 'width="10%"');
		
		
		// A sample detail field with vertical column heading...
#		$group->addDetailField("2022GEParticipation", "Voted 2022?", 'class="verticalCH" width="6%"');

 
		// Summarize the following fields for the summary band.
		$group->addSummaryField("appliedAmt", "Applied to Items");
 
	}

}	


