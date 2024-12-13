<?php

/*
 *	receiptDetailWizard.class.php - These classes are specific to the resources
 *		needed to complete a receipt transaction.
 */

// Define a page component.
class receiptDetailWizard extends wizard {
	
	public function __construct() {
		
		parent::__construct("receiptDetail", "",
							"Receipt Wizard", 
							"Manage Receipts");
		
		$this->resource = "receiptDetail";
		$this->parentResource = "receipt";
		$this->scripts[] = "ui/js/receiptDetail.js";

		
		// Define the steps for the wizard.

		
/*	function addStep() parameters:
			$resource		the target (supporting) resource, 
			$keyFieldName	the key field to filter on in the target resource, 
			$parentKeyField	the related key field in the parent resource, 
			$title, 		This winds up being the name of the step!
			$description	A short user-friendly description for the step. 
 */
		$step = $this->addStep("receipt", "id", "id", "Receipt", 
						"Select or enter a customer receipt.") ;

			// Assign action for completion of the step.
			$step->exitAction = 'onclick="reportTransaction({keyValue})"';

		$step = $this->addStep("contact", "id", "customerNo", "Customer", 
						"Select or enter the customer (billing) contact for this receipt.") ;

			// Present the selection icon for each instance in the table.  
			$step->select = true;
			// Clicking on the icon should update the customer contact for the customer order.
			$step->selectAction = 'onclick="updateCustomer({keyValue})"';

		$stepName = "Apply to Orders";
		$step = $this->addStep("appliedOrder", "customerNo", "customerNo", $stepName, 
						"Apply receipt to orders.") ;
			$step->exitAction = 'onclick="postItemReceipts({keyValue})"';

			// Add a summary band template to present the funds applied to orders.
			$step->summaryBandTemplate="appliedOrder.html";
			
			// Filter for both the parent receipt and the customer.
			$action = $this->addParameters($stepName);
			$menuObj = $this->menu->children[$stepName];
			$menuObj->action = $action;
			

		$stepName = "Apply to Items";
		$step = $this->addStep("appliedItem", "customerNo", "customerNo", $stepName, 
						"Apply receipt to items.") ;
			$step->exitAction = 'onclick="postOrderReceipts({keyValue})"';
			// Add a summary band template to present the funds applied to orders.
			$step->summaryBandTemplate="appliedItem.html";

			// Filter for both the parent receipt and the customer.
			$action = $this->addParameters($stepName);
			$menuObj = $this->menu->children[$stepName];
			$menuObj->action = $action;

	}
	
	
	private function addParameters($stepName){

		// Add unique parameters to launch the step.
		$parameters = array();
		$parameters["receiptNo"] = $this->resultSet[0]["id"];
		$parameters["customerNo"] = $this->resultSet[0]["customerNo"];
		// Convert to JSON...
		$jsonStr = json_encode($parameters);
			
		// Build up the action string...
		$action = "onclick='launchStepWithParameters(" ;
		$action .= "\"{$this->keyValue}\"," ;		// Key value for the parent.
		$action .= "\"{$stepName}\"," ;				// The Step name		
		$action .= $jsonStr;
		$action .= ")'" ;
		
		return $action;
	
	}

}
