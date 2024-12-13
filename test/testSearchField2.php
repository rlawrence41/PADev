<?php 

/* 
 *	testSearchField2.php -- This procedure is the same as testSearchField.php except
 *							it adds a second searchField to test isolation between
 *							the two.
 */

include ("includes.php");
#include ("../contactSearchList.php");

// Add a searchField for a Payee.
$searchField1 = new searchField(
					"payeeId", 
					"Payee", 
					"Enter a Company, Last Name, First Name, or email",
					"contact");

// Add a searchField for a Payee.
$searchField2 = new searchField("title_no", 
					"Title", 
					"Enter the title.  Preface with '%' to search for a keyword.",
					"title");


#print_r($searchField);

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
<?php 
	echo $searchField1->render(); 
	echo $searchField2->render(); 
?>
</form>

</body> 
</html>

