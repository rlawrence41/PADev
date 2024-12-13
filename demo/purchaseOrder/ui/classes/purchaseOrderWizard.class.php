<?php

/*
 *	purchaseOrderWizard.class.php - These classes are specific to the resources
 *		needed to complete a purchase order order transaction.
 */

// Define a page component.
class purchaseOrderWizard extends wizard {
	
	public function __construct() {
		
		parent::__construct("purchaseOrder", "",
							"Purchase Order Wizard", 
							"Manage Purchase Orders");
		
		$this->resource = "purchaseOrder";
		$this->parentResource = "orders";
		$this->scripts[] = "ui/js/purchaseOrder.js";

		
		// Define the steps for the wizard.
		$step = $this->addStep("orders", "id", "id", "Purchase Order", 
						"Select or enter a purchase order.") ;
			// Show only purchase orders.
			$step->persistentFilter = "column[orderType]=P" ;

		$step = $this->addStep("contact", "id", "supplierNo", "Supplier", 
						"Select or enter the supplier for this purchase order.") ;
			// Present the selection icon for each instance in the table.  
			$step->select = true;
			// Clicking on the icon should update the supplier contact for the purchase order.
			$step->selectAction = 'onclick="updateSupplier({keyValue})"';
			
			$selected = $step->select ? "ON" : "OFF";
# debug_msg("Wizard Step Supplier shows select option, {$selected}, and action: {$step->selectAction}. ", true, "PurchaseOrderWizard()");


		$step = $this->addStep("contact", "id", "shipToNo", "Ship To", 
						"Select or enter the ship to contact for this purchase order.") ;
			// Present the selection icon for each instance in the table.  
			$step->select = true;
			// Clicking on the icon should update the ship to contact for the purchase order.
			$step->selectAction = 'onclick="updateShipTo({keyValue})"';


		$step = $this->addStep("contact", "id", "customerNo", "Bill To", 
						"Select or enter the billing contact for this purchase order.") ;
			// Present the selection icon for each instance in the table.  
			$step->select = true;
			// Clicking on the icon should update the customer contact for the purchase order.
			$step->selectAction = 'onclick="updateBillTo({keyValue})"';
		
		$step = $this->addStep("orderItem", "orderKey", "id", "Items", 
						"Enter or edit the detail items for this purchase order.") ;
			$step->addAction = 'onclick="addItem({keyValue})"';
			$step->exitAction = 'onclick="updatePurchaseOrder({keyValue})"';


	}

}
