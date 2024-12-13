<?php

/* 
	Moved to common.php...
	includeResource -- This procedure attempts to do two things:

	1.  Add the folders for a resource application to the include path, and
	2.  Include the class definitions associated with the resource to the current
		script.

 */


include_once ('includes.php');
includeResource("contact");
includeResource("orders");
includeResource("orderItem");
includeResource("receipt");
includeResource("contact");


$includedFiles = get_included_files();
$includePath = explode(":", get_include_path());
?>

<html>
<body>
<h1>Test Includes</h1>

<p align="center"><b><i>
Adding a path to the include path is
NOT the same thing as
including a procedure file!
</i></b></p>

<h2>Included Files:</h2>
<pre>
<?php print_r($includedFiles); ?>
</pre>

<h2>Include Path:</h2>
<pre>
<?php print_r($includePath); ?>
</pre>

<h2>REST Root:</h2>
<p>
<?php echo $GLOBALS['RESTroot']; ?>
</p>

<p><a href="/">Back to the Test menu</a></p>

</body>
</html>