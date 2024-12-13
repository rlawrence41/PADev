<?php
/*
 *	receiptDetailWizard.class.php - These classes are specific to the resources
 *		needed to complete a receiptDetail transaction.
 */

/* A business transaction is a complex set of database transactions that define
 * a business entity.  Examples are customer orders, customer returns, and receipts.

 * A transaction will have a parent resource (or entity) and several supporting 
 * resources--all needed to complete the transaction.

 * A transaction is executed as a wizard.  The wizard guides the user through
 * multiple steps to complete a business transaction.  Each step manages a 
 * supporting resource for the transaction.
 */
 
// Define a transaction wizard component.

class receiptDetailWizard extends wizard {
	
	public function __construct() {
		
		parent::__construct("receiptDetail", "",
							"receiptDetail Transaction Wizard", 
							"Manage receiptDetail Transactions");
		
		$this->resource = "receiptDetail";			
		$this->parentResource = "receipt";
		$this->scripts[] = "ui/js/receiptDetail.js";

/*		function addStep() parameters:
			$resource		the target (supporting) resource, 
			$keyFieldName	the key field to filter on in the target resource, 
			$parentKeyField	the related key field in the parent resource, 
			$title, 		This winds up being the name of the step!
			$description	A short user-friendly description for the step. 
 */



		$step = $this->addStep(
			"receiptDetail", 				// The view used to manage the transaction.
			"id",				 		// usually the resource id field.
			"id",		// foreign key in the parent record.
			"Receipt",				// The id for the step, will appear in the wizard menu.
			"Select or enter a receipt from a customer."			// Will appear when mouse is hovered over menu.
			)

			$step->launchAction = "<<launchAction>>";
			$step->addAction = "";
			$step->exitAction = "reportTransaction({keyValue})";
			$step->selectAction = "";
			$step->summaryBandTemplate = "";


			
			

		$step = $this->addStep(
			"receiptDetail", 				// The view used to manage the transaction.
			"id",				 		// usually the resource id field.
			"customerNo",		// foreign key in the parent record.
			"Customer",				// The id for the step, will appear in the wizard menu.
			"Select or enter the customer (billing) contact for this receipt."			// Will appear when mouse is hovered over menu.
			)

			$step->launchAction = "<<launchAction>>";
			$step->addAction = "";
			$step->exitAction = "";
			$step->selectAction = "updateCustomer({keyValue})";
			$step->summaryBandTemplate = "";


			
			

		$step = $this->addStep(
			"receiptDetail", 				// The view used to manage the transaction.
			"id",				 		// usually the resource id field.
			"receiptNo",		// foreign key in the parent record.
			"Apply to Orders",				// The id for the step, will appear in the wizard menu.
			"Apply receipt to orders and returns."			// Will appear when mouse is hovered over menu.
			)

			$step->launchAction = "<<launchAction>>";
			$step->addAction = "";
			$step->exitAction = "postItemReceipts({keyValue})";
			$step->selectAction = "";
			$step->summaryBandTemplate = "appliedOrder.html";


			
			

		$step = $this->addStep(
			"receiptDetail", 				// The view used to manage the transaction.
			"id",				 		// usually the resource id field.
			"receiptNo",		// foreign key in the parent record.
			"Apply to Items",				// The id for the step, will appear in the wizard menu.
			"Apply receipt to items."			// Will appear when mouse is hovered over menu.
			)

			$step->launchAction = "<<launchAction>>";
			$step->addAction = "";
			$step->exitAction = "postOrderReceipts({keyValue})";
			$step->selectAction = "";
			$step->summaryBandTemplate = "appliedItem.html";


			
			


?>