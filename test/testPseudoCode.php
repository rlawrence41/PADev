<?php

// HTML Templates are in the templates folder.
$eol = "<br/>\n";
include ("includes.php");
include ("page.class.php");
include ("table.class.php");
include ("form.class.php");
include ("fieldGroup.class.php");
include ("field.class.php");

//	Pseudo code for contact admin.

//	Present the header, menu for the page.
$page = new page("Manage Contacts");	// Pass the page title as an argument.

//	Present a table of contacts for editing purposes.  
//	The list should be sortable and contain controls to edit each contact.
//	The list is likely to contain only a subset of the contact fields.
//	Can list fields be identified in the model?
$page->Table = new contactTable();

//	Present a form for editing a particular contact.
//	Fields should be linear to work on a phone if necessary.
//	Fields should be grouped and allow expansion.
//	i.e. Not all fields are likely to be filled in every time.
//	Can groups be identified in the model?
//	Form should provide a "Save" button to save the edited contact.
//	Form should also provide a "Find" button to search for a contact with 
//	the current entries in its fields.
//	Form should be presented as a dialog--overlaying the table.
//	Save or cancel should exit the form and return control to the table.
$form = new contactForm();

// Presenting the page should kick off the process...
$html = $page->Render();
echo $html;
