<?php

/*
 *	customerReturnWizard.class.php - These classes are specific to the resources
 *		needed to complete a customer return transaction.
 */

// Define a page component.
class customerReturnWizard extends wizard {
	
	public function __construct() {
		
		parent::__construct("customerReturn", "",
							"Customer Return Wizard", 
							"Manage Customer Returns");
		
		$this->resource = "customerReturn";
		$this->parentResource = "orders";
		$this->scripts[] = "ui/js/customerReturn.js";

		
		// Define the steps for the wizard.
		$this->addStep("orders", "id", "id", "Customer Return", 
						"Select or enter a customer return.") ;
		// Show only customer returns.
		$this->steps['Customer Return']->persistentFilter = "column[orderType]=R" ;
#		$this->steps['Customer Return']->exitAction = 'onclick="updateCustomerReturn({keyValue})"';

		$this->addStep("contact", "id", "shipToNo", "Return From", 
						"Select or enter the shipping contact for this customer return.") ;
		// Present the selection icon for each instance in the table.  
		$this->steps['Return From']->select = true;
		// Clicking on the icon should update the ship to contact for the customer return.
		$this->steps['Return From']->selectAction = 'onclick="updateShipTo({keyValue})"';
		
		$this->addStep("contact", "id", "customerNo", "Credit To", 
						"Select or enter the billing contact for this customer return.") ;
		// Present the selection icon for each instance in the table.  
		$this->steps['Credit To']->select = true;
		// Clicking on the icon should update the customer contact for the customer return.
		$this->steps['Credit To']->selectAction = 'onclick="updateBillTo({keyValue})"';

		$this->addStep("orderItem", "orderKey", "id", "Items", 
						"Enter or edit the detail items for this customer return.") ;
		$this->steps['Items']->addAction = 'onclick="addItem({keyValue})"';
		$this->steps['Items']->exitAction = 'onclick="updateCustomerReturn({keyValue})"';

		$this->addStep("contact", "id", "supplierNo", "Warehouse", 
						"Select or enter the warehouse to return these items to.") ;
		// Present the selection icon for each instance in the table.  
		$this->steps['Warehouse']->select = true;
		// Clicking on the icon should update the supplier contact for the customer return.
		$this->steps['Warehouse']->selectAction = 'onclick="updateSupplier({keyValue})"';

		$this->addStep("contact", "id", "salesRepNo", "Sales Rep", 
						"Select or enter the sales representative for this customer.") ;
		// Present the selection icon for each instance in the table.  
		$this->steps['Sales Rep']->select = true;
		// Clicking on the icon should update the supplier contact for the customer return.
		$this->steps['Sales Rep']->selectAction = 'onclick="updateSalesRep({keyValue})"';

	}

}
