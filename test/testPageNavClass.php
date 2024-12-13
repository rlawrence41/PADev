<?php

//  This procedure is used to test the page navigation class.  
//	View the page source to verify whether the appropriate HTML was generated.

include_once ("includes.php");
include_once ("contactAdmin.php");

$restURI = "https://" . $_SERVER['SERVER_NAME'] .
			"/common/rest/contact.php/contact";

/* // Test within a bootstrap page to get the styling.
$page = new contactPage();

$html = $page->render();
echo $html; */
?>
<h1>Test Page Navigation Class</h1>
<p>This is a test of the Page Navigation class.</p>
<p>
This component is intended to allow the user to navigate through the 
table data based on the page context.
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

