<?php

/*
 *	user.class.php - These classes are specific to the user resource.
 */

// Define a page component.
class userPage extends page {
	
	public function __construct() {
		
		parent::__construct("user", 
							"userPage", 
							"Manage users");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"user", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage users", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new userTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new userForm("userForm", "adminForm", "user");
		$this->addChild($form); 

	}
}


// Define a table component.
class userTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("user", "id", "users");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","user Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("user_id","user Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
#		$this->addColumn("id","Id");
		$this->addColumn("user_id","User Id");
#		$this->addColumn("contact_no","Contact No");
		$this->addColumn("email","Email");
		$this->addColumn("phone","Phone");
		$this->addColumn("host_no","Host No");
#		$this->addColumn("password","Password");
		$this->addColumn("authCode","AuthCode");
#		$this->addColumn("enteredby","Enteredby");
#		$this->addColumn("employeeid","Employeeid");
#		$this->addColumn("lastupdate","Lastupdate");


	}
}


// Define a form component.
class userForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"user Id", "");
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
		$this->addField("hidden", "id", "Id");
		$this->addField("text", "user_id", "User Id");
#		$this->addField("text", "contact_no", "Contact No");

		// Add a searchField for the Account.
		$searchField1 = new searchField("contact_no", 
							"Account", 
							"Last Name, First Name, Company, or email",
							"contact");


		$this->addField("email", "email", "Email");
		$this->addField("text", "phone", "Phone");
		$this->addField("text", "host_no", "Host No");
		$this->addField("password", "password", "Password");
		$this->addField("text", "authCode", "AuthCode");
		$this->addField("text", "enteredby", "Enteredby");
		$this->addField("text", "employeeid", "Employeeid");
		$this->addField("text", "lastupdate", "Lastupdate");


	}

}


