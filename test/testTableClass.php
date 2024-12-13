<?php

//  This procedure is used to test the table class.  

include_once ("includes.php");
include_once ("contact.class.php");
#include_once ("loginForm.class.php");

$restURI = "https://" . $_SERVER['SERVER_NAME'] .
			"/contact/contactREST.php/contact";

$page = new page("Test Page Navigation Class", "contact", "contact_no", $restURI);

// Set up some context for the page navigation control.
$page->context->lastPage = 150;
$page->context->pageNo = 10;

//  Add the page navigation control and the context context for the container.
//  In this case, the page object is the container for the application.
$pageNav = new pageNav("pageNav", "pageNav1");
$pageNav->addContext($page->context);
$page->addChild($pageNav);

//	Add the table component.
$table = new contactTable();
$page->addChild($table);

$html = $page->render();
echo $html;
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
<p>
<b>The context properties will be hard-coded for the purposes of this test;</b>
but, they should be presented in the text area below.
</p>


<h3>Page Context:</h3>
<p>
<textarea style="height:350px;width:75%;color:red;">
<?php echo $page->context->render(); ?>
</textarea>
</p>
<p><a href="/">Back to the test menu</a></p>

