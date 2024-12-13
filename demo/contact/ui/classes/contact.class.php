<?php

/*
 *	contact.class.php - These classes are specific to the contact resource.
 */

// Define a page component.
class contactPage extends page {
	
	public function __construct() {
		
		parent::__construct("contact", 
							"contactPage", 
							"Manage contacts");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"contact", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage contacts", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new contactTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new contactForm("contactForm", "adminForm", "contact");
		$this->addChild($form); 

	}
}


// Define a table component.
class contactTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("contact", "id", "contacts");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","contact Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("contact_id","contact Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
		$this->addColumn("id","Id");
		$this->addColumn("contactId","Contact Id");
		$this->addColumn("company","Company");
#		$this->addColumn("namePrefix","NamePrefix");
		$this->addColumn("firstName","First Name");
#		$this->addColumn("midName","MidName");
		$this->addColumn("lastName","Last Name");
#		$this->addColumn("nameSuffix","NameSuffix");
		$this->addColumn("poAddr","Postal Addr");
#		$this->addColumn("courAddr","CourAddr");
		$this->addColumn("city","City");
		$this->addColumn("stateAbbr","State Abbr.");
#		$this->addColumn("country","Country");
		$this->addColumn("zipCode","Zip Code");
#		$this->addColumn("munAbbr","munAbbr");
#		$this->addColumn("billTo","BillTo");
		$this->addColumn("phone","Phone");
#		$this->addColumn("phone2","Phone2");
		$this->addColumn("email","Email");
		$this->addColumn("balance", "Account Balance");
#		$this->addColumn("webUrl","WebUrl");
#		$this->addColumn("webservice","Webservice");
#		$this->addColumn("fedIdNo","FedIdNo");
#		$this->addColumn("san","San");
#		$this->addColumn("pubnetId","PubnetId");
#		$this->addColumn("buyerId","BuyerId");
#		$this->addColumn("sellerId","SellerId");
#		$this->addColumn("lExclude","LExclude");
#		$this->addColumn("biography","Biography");
#		$this->addColumn("portrait","Portrait");
#		$this->addColumn("comment","Comment");
#		$this->addColumn("password","Password");
#		$this->addColumn("lAuthor","LAuthor");
#		$this->addColumn("lCustomer","LCustomer");
#		$this->addColumn("lMailList","LMailList");
#		$this->addColumn("lSalesRep","LSalesRep");
#		$this->addColumn("lSupplier","LSupplier");
#		$this->addColumn("lWarehouse","LWarehouse");
#		$this->addColumn("lEmployee","LEmployee");
#		$this->addColumn("lApproved","LApproved");
#		$this->addColumn("updatedBy","Updatedby");
#		$this->addColumn("userNo","User No.");
#		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class contactForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"contact Id", "");
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
		$this->addField("text", "contactId", "Contact Id");
		$this->addField("text", "company", "Company");
		$this->addField("text", "namePrefix", "NamePrefix");
		$this->addField("text", "firstName", "First Name");
		$this->addField("text", "midName", "Middle Name");
		$this->addField("text", "lastName", "Last Name");
		$this->addField("text", "nameSuffix", "Name Suffix");
		$this->addField("textarea", "poAddr", "Postal Addr.");
		$this->addField("textarea", "courAddr", "Courier Addr.");
		$this->addField("text", "city", "City");
		$this->addField("text", "stateAbbr", "State Abbr.");
		$this->addField("text", "country", "Country");
		$this->addField("text", "zipCode", "ZipCode");
		$this->addField("text", "munAbbr", "Municipal Abbr.");
		$this->addField("text", "billTo", "Bill To");
		$this->addField("text", "phone", "Phone");
		$this->addField("text", "phone2", "Phone2");
		$this->addField("email", "email", "Email");
		$this->addField("text", "webUrl", "Web URL");
		$this->addField("text", "webservice", "Web Service");
		$this->addField("text", "fedIdNo", "Fed Id No");
		$this->addField("text", "san", "SAN");
		$this->addField("text", "pubnetId", "Pubnet Id");
		$this->addField("text", "buyerId", "Buyer Id");
		$this->addField("text", "sellerId", "Seller Id");
		$this->addField("checkbox", "lExclude", "Exclude from Mail List?");
		$this->addField("textarea", "biography", "Biography");
		$this->addField("text", "portrait", "Portrait");
		$this->addField("text", "comment", "Comment");
		$this->addField("password", "password", "Password");
		$this->addField("checkbox", "lAuthor", "Author?");
		$this->addField("checkbox", "lCustomer", "Customer?");
		$this->addField("checkbox", "lMailList", "MailList?");
		$this->addField("checkbox", "lSalesRep", "SalesRep?");
		$this->addField("checkbox", "lSupplier", "Supplier?");
		$this->addField("checkbox", "lWarehouse", "Warehouse?");
		$this->addField("checkbox", "lEmployee", "Employee?");
		$this->addField("checkbox", "lApproved", "Approved");
		$this->addField("readonly", "updatedBy", "Updated by");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("readonly", "lastUpdated", "Last Updated");


	}

}


