<?php

/*
 *	customer.class.php - These classes are specific to the customer resource.
 */

// Define a page component.
class customerPage extends page {
	
	public function __construct() {
		
		parent::__construct("customer", 
							"customerPage", 
							"Manage customers");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"customer", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage customers", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new customerTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new customerForm("customerForm", "adminForm", "customer");
		$this->addChild($form); 

	}
}


// Define a table component.
class customerTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("customer", "id", "customers");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","customer Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("customer_id","customer Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
#		$this->addColumn("id","Id");
#		$this->addColumn("customerNo","Customer #");
		$this->addColumn("customerNoSearch","Customer");
		$this->addColumn("crcrdVndr","Credit Card Vender");
		$this->addColumn("crcrdAcct","Credit Card Account");
		$this->addColumn("crcrdexpdt","Credit Card Expire Date");
#		$this->addColumn("salesRepNo","Sales Rep No.");
		$this->addColumn("salesRepNoSearch","Sales Representative");
		$this->addColumn("taxExempt","Tax Exempt?");
		$this->addColumn("discount","Discount (%)");
		$this->addColumn("terms","Terms");
#		$this->addColumn("termsDesc","Terms Description");
#		$this->addColumn("credLimit","Credit Limit");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User No.");
#		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class customerForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"customer Id", "");
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
		$this->addField("readonly", "customerNo", "customerNo");

		// The searchField component needs more explanation...
		$searchField = new searchField("customerNo", 				// Name of the field to be saved in this instance
					"Customer", 									// Label for the search field (appears in the form)
					"Enter an ID, company, last name, or email",	// Description (used as a placeholder)
					"contact");										// Resource to search

		$this->addChild($searchField);

		$this->addField("text", "crcrdVndr", "Credit Card Vender");
		$this->addField("text", "crcrdAcct", "Credit Card Acct");
		$this->addField("text", "crcrdexpdt", "Credit Card Expire Date");
		$this->addField("readonly", "salesRepNo", "Sales Rep#");
		
		// The searchField component needs more explanation...
		$searchField = new searchField("salesRepNo", 				// Name of the field to be saved in this instance
					"Sales Representative", 						// Label for the search field (appears in the form)
					"Enter an ID, company, last name, or email",	// Description (used as a placeholder)
					"contact");										// Resource to search

		$this->addChild($searchField);		
		
		$this->addField("checkbox", "taxExempt", "Tax Exempt?");
		$this->addField("text", "discount", "Discount");
		$termsField = $this->addField("select", "terms", "Terms");
			// Assign a list of options and define which should be selected.
			$termsField->options = ["Prepaid", 
									"Consignment", 
									"Net 30 days", 
									"Net 60 days", 
									"Net 90 days", 
									"Net 120 days", 
									"No Royalty", 
									"Upon Receipt", 
									"Pro Forma", 
									"Per Contract", 
									"Other", 
									"Review Copy"];
			$termsField->addOptions("Prepaid");

		$this->addField("text", "termsDesc", "Terms Description");
		$this->addField("text", "credLimit", "Cred Limit");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


