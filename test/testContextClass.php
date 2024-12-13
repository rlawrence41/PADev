<?php

// This procedure is used to test the context class.  

include_once ("includes.php");

$context = new context("contact", "contact_no", $restURI);

// Page number and last page can be set here.
	$context->pageNo = 1;
	$context->lastPage = 1000;

echo $context->render();
?>
<h1>Test Context</h1>
<p>
The Context is a container for properties used to manage the resource. These 
include the name of the resource, the REST URI for the resource, the current
and last pages, which may be based on the filter and sort order.  
In other words, the context will dictate which records will be displayed on 
the page.
 </p>
 
 <p>
 The Context class will render the context as Javascript variables.  These
 variables will be used by other Javascript procedures, such as those found in
 <i>pageActions.js</i>.
 </p>

<h3>Context:</h3>
<p>
<textarea style="height:350px;width:75%;color:red;">
<?php echo $context->render(); ?>
</textarea>
</p>

<h3>Get QueryStr:</h3>
<p>
<textarea style="height:50px;width:75%;color:red;">
<?php echo $context->queryStr(); ?>
</textarea>
</p>




<p><a href="/">Back to the test menu</a></p>