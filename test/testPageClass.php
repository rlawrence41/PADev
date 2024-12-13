<?php

//  This procedure is used to test the table class. 
//	It uses the contact resource as an example. 

include_once ("includes.php");
include_once ("loginForm.class.php");

// The following variables are specific to the contact resource.
$title = "Test Page Class";
$resource = "contact";
$keyFieldName = "contact_no";
$restURI = "https://" . $_SERVER['SERVER_NAME'] .
			"/contact/contactREST.php/contact";

// Create the page.
$page = new page($title, 
				$resource, 
				$keyFieldName, 
				$restURI);

// Add columns to the table that are specific to the contact resource.
addColumnsToTable($page->table);

// Add fields to the form that are specific to the contact resource.
addFieldsToForm($page->form);

echo $page->render();




function addColumnsToTable($table){
	
	// Add table columns specific to managing contacts.
#	$table->addColumn("contact_no","Contact Number");	// No need to show in the table.
	$table->addColumn("contact_id","Contact Id", "A short, memorable name for the account");
	$table->addColumn("company","Company", "Company or organization name");
	$table->addColumn("last_name", "Last Name");
	$table->addColumn("first_name", "First Name");
	$table->addColumn("po_addr", "Street", "Street address according to the postal service");
	$table->addColumn("city", "City", "City or town");
	$table->addColumn("state_abbr", "State", "State/Prov. Abbr.");
	$table->addColumn("zip_code", "Zip Code", "Postal Code");
	$table->addColumn("country", "Country");
	$table->addColumn("email", "Email", "Email Address");

}


function addFieldsToForm($form){

	// Identifiers
	$fieldGroup = $form->addFieldGroup("Identifiers", true);
	$fieldGroup->addField("hidden", "contact_no", "contact_no", "Contact Number", "");
	$fieldGroup->addField("text", "contact_id", "contact_id", "Contact Id", "Short, memorable name for the account");
	$fieldGroup->addField("text", "company", "company", "Company", "Company or organization name");
	$fieldGroup->addField("text", "nameprefix", "nameprefix", "Prefix", "e.g.: Mr., Mrs., Ms., Dr., etc.");
	$fieldGroup->addField("text", "first_name", "first_name", "First Name", "first name");
	$fieldGroup->addField("text", "mid_name", "mid_name", "Middle Name", "middle name or initial");
	$fieldGroup->addField("text", "last_name", "last_name", "Last Name", "last name");

	// Address fields...
	$fieldGroup = $form->addFieldGroup("Address");
	$fieldGroup->addField("textarea", "po_addr", "po_addr", "Street", "Postal Service Street Address ");
	$fieldGroup->addField("text", "city", "city", "City", "City or town");
	$fieldGroup->addField("text", "state_abbr", "state_abbr", "State", "State Abbreviation");
	$fieldGroup->addField("text", "country", "country", "Country", "USA");
	$fieldGroup->addField("text", "zip_code", "zip_code", "Zip Code", "Postal Code");
	$fieldGroup->addField("textarea", "cour_addr", "cour_addr", "Courier Address", "Complete physical address for courier service");
	$fieldGroup->addField("text", "countyabbr", "countyabbr", "County", "County Abbreviation");

	// Contact fields...
	$fieldGroup = $form->addFieldGroup("Contact");
	$fieldGroup->addField("text", "phone", "phone", "Phone", "Enter the primary phone number.");
	$fieldGroup->addField("text", "phone2", "phone2", "Phone2", "Enter an alternate phone, FAX or cell number.");
	$fieldGroup->addField("email", "email", "email", "Email", "e.g. username@domain.com");
	$fieldGroup->addField("text", "web_url", "web_url", "Web Site", "Enter the URL of this contact's web site.");
	$fieldGroup->addField("text", "webservice", "webservice", "Web Service", "Enter the URL of a web service offered by this contact.");
	
}

?>

<hr/>
<p>This is a test of the <b>Table</b> class.</p>
<p>
This component presents a page worth of records in a table based on the 
current context.
</p>

<p>
The context variables will be altered based on the actions the user takes 
while running the application.  For example, setting a filter will result
in fewer records in the result--and therefore fewer pages to navigate.
</p>

<h3>Page Context:</h3>
<p>
<textarea style="height:350px;width:75%;color:red;">
<?php echo $page->context->render(); ?>
</textarea>
</p>
<p><a href="/">Back to the test menu</a></p>

