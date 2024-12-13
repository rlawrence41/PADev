<?php 

/* 
 *	testSearchList3.php -- 3rd attempt at testing the search list class.
 *	NOTE: The searchList.class.php has been moved to the REST folder.
 *	The searchlist function works based on key presses in the search field.
 *	With each key pressed, the value of the search field is submitted to 
 *	the searchList page in the REST API.  What is returned is the HTML content
 *	of the list, based on the results of the search.
 */

$eol = "<br/>\n";
include ("includes.php");
//include ("searchList.class.php");
//include ("contactRequest.class.php");
?>

<!DOCTYPE html>
<html>
<head>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="/ui/js/searchList.js"></script>
</head>
<body>

<h2>Test the searchList Class</h2>

<form action="#">
	<div class='form-group'>
		<input type='text' 
			class='form-control' 
			id='payeeId' 
			name="column[payeeId]" 
			value="0"
			disabled/>
		<label for='payeeIdSearch'>Payee</label>
		<input type='text' 
			class='form-control' 
			id='payeeIdSearch' 
			name="column[payeeIdSearch]" 
			value=""
			placeholder='Last name, first name, company'
			autocomplete="off"
			onfocus="repositionList(this)"
			onkeyup="refreshList(this.value)"/>
	</div>
</form>
<style>

	/* The following dictates the appearance of the search list */
	div.searchList {
		position: absolute; /* to position directly below the input element that activates the list. */
		background-color: white;
		padding: 10px;
		box-shadow: 10px 10px 30px gray;
		border: 1px solid gray;
		border-radius: 5px;
	}

	div.searchList ul {
	  list-style-type: none ;
	  padding-inline-start: 0px;
	}

	div.searchList li:hover {
	  background-color: lightgray;
	}

	div.searchList a:link {
	  text-decoration: none;
	}

</style>
<div class="searchList"><ul><li>The search list is empty.</li></ul></div>
<script>
/*
	refreshList() - An AJAX request to refresh the list with potentially new data.
				This procedure is specific to each search list object in the page.
 */
function refreshList(searchVal) {
	
	// The URI for the REST API call...
	var thisURI = "/rest/contactSearchList.php/contact";
	thisURI += "?searchVal=" + searchVal;

	// Instantiate the request object.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState == 4 &&	this.status == 200) {

			// Save the response to the searchList division.
			var searchList = this.responseText;
			$(".searchList").html(searchList);	// Update the list.
			
		}
	}
	xhttp.open("GET", thisURI, true);  //"True" indicates asynchronous!
	xhttp.send();

}

</script>
</body> 
</html>
