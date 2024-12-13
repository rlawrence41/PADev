<?php

// The Authorization resource facilitates the sign-in process.

/*
 *	auth.class.php - These classes are specific to the auth resource.
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
 *			Most of the features of the login process and profile 
 *			management can be found here.
 */

/*
//	See user.class.php for the components to administer user accounts--
//	including authorizations.  This negates the need for a page and table 
//	component for authorizations.

 // Define a page component.
class authPage extends page {
	
	public function __construct() {
		
		parent::__construct("auth", 
							"authPage", 
							"Manage Accounts");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"auth", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage Accounts", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new authTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new authForm("authForm", "adminForm", "Account Credentials");
		$this->addChild($form); 

	}
}


// Define a table component.
class authTable extends table {

 	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("auth", "id", "Accounts");
		
		// Add table columns specific to managing authorizations.
#		$this->addColumn("id");
		$this->addColumn("user_id","User Id", "A short, memorable name for the account");
		$this->addColumn("contact_noSearch", "Profile", "Associated contact profile");
		$this->addColumn("email", "Email", "Email Address");
		$this->addColumn("phone", "Cell Phone"); 	
		$this->addColumn("authCode", "Authorization Code");

	}
}



// Define the adminForm component.
// From the page component above: 	
//		$form = new authForm("authForm", "adminForm", "Account Credentials");
class authForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		// Define the field specific to the auth(entication) resource.

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
		$this->addField("password", "password", "Password", "Enter your password.");
#		authCode

	}
}

*/

// Define the loginForm..
class loginForm extends form {

	public $template = "formStandard.html";

	public function __construct() {
		
		parent::__construct("loginForm", "loginForm", "Sign-In");

		$this->addField("hidden", 	"id");
		$this->addField("text",		"user_id", "User Id", "Enter your username for this account");
		$this->addField("password", "password", "Password", "Enter your password.");
		$this->addfield("checkbox", "register", "Create a new account", "Check if you need to register as a new user.");
		$this->addfield("checkbox", "forgot", "Forgot my password", "Check if you need to reset your password.");

		// Remove the checkbox elements from the data-set class for updating.
		$this->children['forgot']->doNotSave();
		$this->children['register']->doNotSave();
		
		$this->action = 'onclick="login()"';
	}
}


// Define the registration form component.
class registerForm extends form {

	public $template = "formStandard.html";

	public function __construct() {
		
		parent::__construct("registerForm", "registerForm", "Reset Password");

#		$this->addField("hidden", 	"id");
		$this->addField("text",		"user_id", "User Id", "Short, memorable name for this account");
		$this->addField("email", 	"email", "Email", "e.g. username@domain.com");
		$this->addField("text", 	"phone", "Cell Phone", "Enter your cell phone for SMS messages."); 	

		// Add a searchField for the phone service.
		$searchField1 = new searchField("host_no", 
							"Cell Phone Service", 
							"Please select your phone hosting service.",
							"smsGateway");
		$this->addChild($searchField1);

		$this->addField("password", "password", "Password", "Enter your desired password.");
		$this->addField("password", "password2", "Confirm Password", "Please enter your password again to confirm.");
		
		// Remove this second password field from the data-set class for updating.
		$this->children['password2']->doNotSave();

		$this->action = 'onclick="register()"';

	}
}


// Define the resetPasswordForm..
class resetPasswordForm extends form {

	public $template = "formStandard.html";

	public function __construct() {
		
		parent::__construct("resetPasswordForm", "resetPasswordForm", "Reset Password");

		$this->addField("hidden", "id");
		$this->addField("readonly",	"user_id", "User Id");
		$this->addField("password", "password", "Password", "Enter your new password.");
		$this->addField("password", "password2", "Confirm Password", "Enter your password again to confirm.");
		
		// Remove this second password field from the data-set class for updating.
		$this->children['password2']->doNotSave();

		$this->action = 'onclick="resetPassword()"';
	}
}


// Define the securityCodeForm..
class securityCodeForm extends form {

	public $template = "formStandard.html";

	public function __construct() {
		
		parent::__construct("securityCodeForm", "securityCodeForm", "Security Code?");

		$this->addField("hidden", "id");
		$this->addField("readonly",	"user_id", "User Id");
		$this->addfield("text", 	"securityCode", "Security Code", "Enter the security code we sent you.");
		$this->addfield("checkbox", "resend", "Please send the code again.", "Check here if you need us to resend the code.");

		// Remove the checkbox elements from the data-set class for updating.
		$this->children['resend']->doNotSave();

		$this->action = 'onclick="securityCode()"';
	}
}


// Define the whoAreYouForm..
class whoAreYouForm extends form {

	public $template = "formStandard.html";

	public function __construct() {
		
		parent::__construct("whoAreYouForm", "whoAreYouForm", "Who are you?");

		$this->addField("hidden", "id");
		$this->addField("text",	  "user_id", 	"User Id", "Enter your username for this account.");
		$this->addField("email",  "email",	"Email", "Enter the email address associated with this account.");
		$this->addField("text",   "phone",	"Cell Phone", "Enter your cell phone associated with this account."); 	
		$this->addfield("radio",  "notifyMail",	"Send via email", "We'll send a security code to the account email.");

		// Default to notifying by email.
		$this->children['notifyMail']->value = 1;
#		$this->addfield("radio",  "notifyText",	"Send a text message", "We'll send a security code to via SMS text to the cell phone on record.");

		// Remove the radio button elements from the data-set class for updating.
		$this->children['notifyMail']->doNotSave();
#		$this->children['notifyText']->doNotSave();

		$this->action = 'onclick="whoAreYou()"';
	}
}

