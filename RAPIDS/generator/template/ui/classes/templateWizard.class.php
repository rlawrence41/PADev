<?php
/*
 *	<<resource>>Wizard.class.php - These classes are specific to the resources
 *		needed to complete a <<resource>> transaction.
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

class <<resource>>Wizard extends wizard {
	
	public function __construct() {
		
		parent::__construct("<<resource>>", "",
							"<<resource>> Transaction Wizard", 
							"Manage <<resource>> Transactions");
		
		$this->resource = "<<resource>>";			
		$this->parentResource = "<<parentResource>>";
		$this->scripts[] = "ui/js/<<resource>>.js";

/*		function addStep() parameters:
			$resource		the target (supporting) resource, 
			$keyFieldName	the key field to filter on in the target resource, 
			$parentKeyField	the related key field in the parent resource, 
			$title, 		This winds up being the name of the step!
			$description	A short user-friendly description for the step. 
 */

<<wizardSteps>>


?>