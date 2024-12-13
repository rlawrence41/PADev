<?php


/*
 *	project.class.php - These classes are specific to the project resource.
 */

// Define a page component.
class projectPage extends page {
	
	public function __construct() {
		
		parent::__construct("project", 
							"projectPage", 
							"Manage Projects");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"project", 
								"project_no");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage Projects", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new projectTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new projectForm("projectForm", "adminForm", "project");
		$this->addChild($form); 

	}
}


// Define a table component.
class projectTable extends table {

    function __construct() {

		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("project", "project_no", "Projects");
		
		// Add table columns specific to managing projects.
//		$this->addColumn("project_no","Project Number");
		$this->addColumn("project_id","Project Id");
		$this->addColumn("projectname","Project Name");
		$this->addColumn("estimated_cost", "Estimated Cost");
		$this->addColumn("status", "Status");
	}
}


class projectForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);
		// addField(fieldType, fieldName, columnTitle, description)
		$this->addField("hidden",	"project_no");
		$this->addField("text",		"project_id", 	"project Id", "Unique Id for the project");
		$this->addField("textarea",	"projectname", 	"project");
		$this->addField("textarea",	"description_url", 	"Description Page");
		$this->addField("text",		"estimated_hours",	"Estimated Hours");
		$this->addField("text",		"estimated_cost",	"Estimated Cost");
		$this->addField("date",		"start_date",	"Start Date");
		$this->addField("date",		"end_date",		"End Date");
		$this->addField("text",		"status",		"Project Status");
		$this->addField("textarea",	"comment",		"Comment", "Any notes you want to keep on the project");
		$this->addField("checkbox",	"approved",		"Approved?", "Is the project is approved for presentation?");
		
	}
}
