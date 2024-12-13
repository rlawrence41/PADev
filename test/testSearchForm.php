<?php

// This procedure is used to test the search form class.  

$eol = "<br/>\n";
include ("includes.php");
include ("searchContactForm.class.php");

$form = new searchContactForm("Contact");	// Parameter describes what we are editing.
$htmlForm = $form->render();

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- FavIcon for this site -->
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Test Search Contact Form</title>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  </head>
  <body>
    <h1>Test Select Contact Form</h1>

<button type="button" 
	id="search" 
	class="btn btn-primary"
	onclick=newSearch()>
	Search Contacts
</button>

<?php echo $htmlForm; ?>

<h2>Search List:</h2>

    <!-- Optional JavaScript -->

	<script>
	
/*
 *	The following variables retain the context for the page.
 */
var resource 	= "{resource}";
var pageNo		= {pageNo};
var lastPage	= 0;
var sortBy 		= "" ;
var filterStr 	= "" ;
var restURI 	= "{localRESTURI}" ;

function getList(){

	// Build a query string from the form contents.
	filterStr = queryStrFromForm("searchContactForm");	
	//$("#searchList").html(filterStr);	// Test the list with the Query String.

	//alert("Requesting data to build a list using the following:" + filterStr);
	
	// Call the REST API to get a list of contacts.
	if (filterStr.length > 0) {refreshList();}
		
}


function json2String(json){
	var instance, instanceStr;
	var list = "";
	var jsonArray = JSON.parse(json)

	for (n in jsonArray){
		instance = jsonArray[n];
		instanceStr = "";
		for (i in instance){
			if (instance[i].length > 0){
				if (instanceStr.length > 0) {instanceStr += ", " }
				instanceStr += instance[i];
			}
		}
		list += instanceStr + "<br/>\n";
	}

	return list;
}
/*
 *	NewSearch - User requests a new filter by a click on the Search control.
 */
function newSearch(){

	// Prepare the form to collect a new filter condition.
	setFormToSearchMode();

	// Show the modal form.
	$('#adminFormDiv').modal('show');

}


/*
	queryStrFromForm(formId) - This function gathers <input> element values into a 
						query string.  This function can be used on different 
						forms, and thus, needs the form Id.
	
 */
function queryStrFromForm(formId) {

	// Bootstrap input elements are all in class "form-control".  
	// Capture the list of form controls.
	var myform = document.getElementById(formId);
	var inputList = myform.getElementsByClassName("form-control");
    var queryStr = "";
    var i, fieldName, fieldValue;
    for (i = 0; i < inputList.length ;i++) {
		fieldName = inputList[i].id;
		fieldValue = inputList[i].value;
		if (fieldValue.length > 0) {
			if (queryStr.length > 1) {queryStr+="&";}
			// Note that queryStr does NOT require quotes around column indices or 
			// field values.
        	queryStr += "column[" + fieldName + "]=" + fieldValue;
		}
    }
	
    return queryStr;
}


/*
	refreshList() - An AJAX request to refresh the list with potentially new data.
 */
function refreshList() {
	
	//window.location.hostname + 
	var restURI = "../rest/contact.php/contact?limit=10&sortBy=last_name" ;
	if (filterStr.length > 0) {
		restURI += "&" + filterStr;		// Add the filter to the query string.
	}

	//alert("Ready to make an AJAX call to: " + restURI);
	//$("#searchList").html(restURI);	// Test the list with the URI.
	//return;


	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState == 4 &&	this.status == 200) {
			// Save the response to the table division on the page.
			var jsonString = json2String(this.responseText);
			$("#searchList").html(jsonString);	// Update the list.
//			$("#searchList").css("visibility", "visible");
		}
	}
	xhttp.open("GET", restURI, true);  //"True" indicates asynchronous!
	xhttp.send();

}

/*
 *	setFormToSearchMode - Sets the modal form up for collecting search criteria.
 */

function setFormToSearchMode() {

	// Set the form's action collect a filter condition.
	$('#formDescription').html("Use form fields to search.");
	$('#continue').off("click");		// Remove previous click event handler.
	$('#continue').html("Search");
	$(".form-control").val("");			// Clear all form controls.
	$('#continue').click(function (){
		getList();
		// Hide the search form.
		$('#adminFormDiv').modal('hide');
	});
}



function loadList(){

	// Stuff the response into the list DIV container.
	document.getElementById("searchList").innerHTML = list;

}

</script>

</body>
</html>