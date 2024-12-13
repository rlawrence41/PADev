<?php

include "includes.php";

//	Test the getInstance() Javascript function in /ui/js/pageActions.js.

?>


<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script>
	
/*
 *	The following variables retain the context for the page.
 */

var pageNo = 0;
var lastPage = 0;
var sortBy = "contact_no" ;
var filterStr = "" ;
var restURI = "../rest/contact.php/contact" ;
//var restResponse = "Some initial value.";

$(document).ready(function(){
  
  // The "Go" button click starts the process.
  $("#go").click(function() {
	var keyFieldValue = $("#keyField").val();
	alert ("Calling getInstance with " + keyFieldValue);
	getInstance(keyFieldValue);
  });
  
  // The change button simply changes the content of the restResponse element.
  $("#change").click(function() {
		$("#restResponse").html("This element was changed.");
  });
  
  // When getInstance completes, the "restResponse" element will be changed.
  $("#restResponse").change(function() {
		responseDetails();
//	alert("Would have called responseDetails().")
  });

  // Hide or Show the response.
  $("#hide").click(function(){
    $("#restResponse").hide();
  });
  
  $("#show").click(function(){
	$("#restResponse").show();
  });

});


function responseDetails() {
	
	// Capture the selected resource.
	var responseText, jsonObj, jsonData;
	var details = "";
	var eol = "<br/>\n";
	restResponse = $('#restResponse').text();
	alert ("REST Response: " + restResponse) ;
	
	details = "Response data type: " + typeof(responseText) + eol;
 	try {
		jsonObj = JSON.parse(restResponse);
	}
	catch(err){
		alert("Error: " + err.name + "<br>" + err.message);
		return;
	} 

	details += "JSON Object data type: " + typeof(jsonObj) + eol;
	jsonData = jsonObj[0];
	details += "JSON data type: " + typeof(jsonData) + eol;
	
	// Loop through the fields of the json record to update field values in the 
	// administrative form.
	var fieldName;
	for (fieldName in jsonData) {
	  fieldVal = jsonData[fieldName];
	  details += fieldName + ":  " + fieldVal + eol;
	}

	document.getElementById("details").innerHTML = details;

}

/* 
 *  GetInstance(keyValue) – calls the REST API to read an instance from the 
 *							database.
 */
function getInstance(keyValue){
	
	var resourceURI = restURI + "/" + keyValue;
	alert("GetInstance is calling: " + resourceURI);
	var jsonData;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
			$("#restResponse").trigger("change");
		} 
	}
	xhttp.open("GET", resourceURI, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send();
}



</script>

</head>
<body>
	<h1>Test GetInstance()</h1>

	<p>This page will test the getInstance() javascript function used in the UI framework.</p>

	<p>Contact Number: <input type="text" id="keyField" value="67586" /> 
	<button id="go">Go</button>
	<button id="change">Test</button>
	</p> 

	<p>Click on the "Go" button to retrieve a contact instance.</p>

    <!-- A place to store the response from the REST API -->
	<div class="container-fluid" id="table">
	<textarea id="restResponse" rows="4" cols="50" hidden>The REST API response should show here.
	</textarea>
	</div>

	<button id="hide">Hide</button>
	<button id="show">Show</button>

	<h2>JSON Data Response Details</h2>
	<p id="details">Details should show up here.</p>

	<!--script src="/ui/js/pageActions.js"></script-->

</body>
</html>
