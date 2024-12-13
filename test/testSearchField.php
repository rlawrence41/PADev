<?php 

/* 
 *	testSearchField.php -- This procedure instantiates a contact search 
 *	field.  A "search field" is actually a collection of fields and a 
 *	search list.
 */

include ("includes.php");
includeResource("contact");
#include ("contactSearchList.php");


// Add a searchField for a Payee.
$searchField = new searchField(
					"payeeId", 
					"Payee", 
					"Enter a Company, Last Name, First Name, or email",
					"contact");

#print_r($searchField);

// For troubleshooting, capture the include path and files...
$includedFiles = get_included_files();
$includePath = explode(":", get_include_path());

?>

<!DOCTYPE html>
<html>
<head>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="common/ui/css/searchList.css">

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="common/ui/js/searchList.js"></script>
<script>
$(document).ready(function(){			

	// Hide any search lists--since they will be empty.
	$(".searchList").hide();

});
</script>
</head>
<body>

<h2>Test the searchField Class</h2>

<form action="#">
<?php echo $searchField->render(); ?>
</form>

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


