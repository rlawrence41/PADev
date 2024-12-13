<?php

/*
 *	user.class.php - These classes are specific to the user resource.
 *
 *			There is serious overlap between the user and auth resources.
 *			They operate on the same table table in the database.  The 
 *			difference, however, is that the auth resource is responsible
 *			for authenticating a user, and supporting the processes to 
 *			allow the user to manage their own account profile.
 *
 *			The user resource, by contrast, is meant to to facilitate the
 *			administration of all user accounts.  Accounts can be created
 *			or deleted, and specific authorizations can set by an 
 *			administrator (someone other than the user).
 *
 */

// Define a page component.
class userPage extends page {
	
	public function __construct() {
		
		parent::__construct("user", 
							"userPage", 
							"Manage User Accounts");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"user", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage User Accounts", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new userTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new userForm("userForm", "adminForm", "User Account");
		$this->addChild($form); 

		// Add a second form to reset the password.
		$form = new userForm("passwordForm", "pwForm", "Reset Password");
		$this->addChild($form); 

	}
}


// Define a table component.
class userTable extends table {

 	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("user", "id", "Accounts");
		
		// Add table columns specific to managing users.
#		$this->addColumn("id");
		$this->addColumn("user_id","User Id", "A short, memorable name for the account");
		$this->addColumn("contact_noSearch", "Profile", "Associated contact profile");
		$this->addColumn("email", "Email", "Email Address");
		$this->addColumn("phone", "Cell Phone"); 	
		$this->addColumn("authCode", "Authorization Code");

	}

/*	This section was removed because resetting the password is now accomplished
	by the login process, and specifically, the resetPassword.php page.

 	// 	Sub-classing the action buttons to present an extra button for resetting 
	//	passwords.  It must be sub-classed here because each set of buttons
	//	is rendered for each record in the collection.
	public function addActionButtons($row, $record) {
		$cellName = "Actions"; 
		$cell = new actionButtons($cellName);
		$cell->template = "tableActionButtons_pw.html";	// Presents the extra icon.
		$cell->keyValue = $record[$this->keyFieldName];
		$row->addChild($cell);
	}
 */
}


// Define the adminForm component.
// From the page component above: 	
//		$form = new userForm("userForm", "adminForm", "User Account");
class userForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		// Define the fields specific to the user resource.

		$this->addField("hidden", 	"id");
		$this->addField("text",		"user_id", "User Id", "Short, memorable name for this account");

		// Add a searchField for the Account.
		$searchField1 = new searchField("id", 
							"Account", 
							"Last Name, First Name, Company, or email",
							"contact");
		$this->addChild($searchField1);

		$this->addField("email", 	"email", "Email", "e.g. username@domain.com");
		$this->addField("text", 	"phone", "Cell Phone", "Enter your cell phone for SMS messages."); 	
		$this->addField("text",		"authCode",	"Authorization Code");
		$this->addField("message",	"pwMessage", "Note:", "Enter a password ONLY if you wish to clear the current password.");
		$this->addField("password", "password", "Password", "Enter your password.");

		// Remove the password message element from the data-set class for updating.
		$this->children['pwMessage']->classList = "";


	}
}

