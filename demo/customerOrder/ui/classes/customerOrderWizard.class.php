<?php

/*
 *	customerOrderWizard.class.php - These classes are specific to the resources
 *		needed to complete a customer order transaction.
 */

// Define a page component.
class customerOrderWizard extends wizard {
	
	public function __construct() {
		
		parent::__construct("customerOrder", "",
							"Customer Order Wizard", 
							"Manage Customer Orders");
		
		$this->resource = "customerOrder";
		$this->parentResource = "orders";
		$this->scripts[] = "ui/js/customerOrder.js";

		
		// Define the steps for the wizard.
		$this->addStep("orders", "id", "id", "Customer Order", 
						"Select or enter a customer order.") ;
		// Show only customer orders.
		$this->steps['Customer Order']->persistentFilter = "column[orderType]=C" ;
#		$this->steps['Customer Order']->exitAction = 'onclick="updateCustomerOrder({keyValue})";

		$this->addStep("contact", "id", "shipToNo", "Ship To", 
						"Select or enter the ship to contact for this customer order.") ;
		// Present the selection icon for each instance in the table.  
		$this->steps['Ship To']->select = true;
		// Clicking on the icon should update the ship to contact for the customer order.
		$this->steps['Ship To']->selectAction = 'onclick="updateShipTo({keyValue})"';
		
		$this->addStep("contact", "id", "customerNo", "Bill To", 
						"Select or enter the billing contact for this customer order.") ;
		// Present the selection icon for each instance in the table.  
		$this->steps['Bill To']->select = true;
		// Clicking on the icon should update the customer contact for the customer order.
		$this->steps['Bill To']->selectAction = 'onclick="updateBillTo({keyValue})"';

		$this->addStep("orderItem", "orderKey", "id", "Items", 
						"Enter or edit the detail items for this customer order.") ;
		$this->steps['Items']->addAction = 'onclick="addItem({keyValue})"';
		$this->steps['Items']->exitAction = 'onclick="updateCustomerOrder({keyValue})"';

		$this->addStep("contact", "id", "supplierNo", "Ship From", 
						"Select or enter the warehouse contact to ship this customer order from.") ;
		// Present the selection icon for each instance in the table.  
		$this->steps['Ship From']->select = true;
		// Clicking on the icon should update the supplier contact for the customer order.
		$this->steps['Ship From']->selectAction = 'onclick="updateSupplier({keyValue})"';

		$this->addStep("contact", "id", "salesRepNo", "Sales Rep", 
						"Select or enter the sales representative for this customer.") ;
		// Present the selection icon for each instance in the table.  
		$this->steps['Sales Rep']->select = true;
		// Clicking on the icon should update the supplier contact for the customer order.
		$this->steps['Sales Rep']->selectAction = 'onclick="updateSalesRep({keyValue})"';

	}

}
