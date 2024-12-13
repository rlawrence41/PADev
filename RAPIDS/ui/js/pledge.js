/*
 *	pledge.js - These javascript procedures support the pledge application.
 *
 *			The user must be able to enter a new pledge, modify an existing
 *			pledge, or delete a previously entered pledge.
 *
 */

 
/* 
 *  deletePledge(project_no) – calls the REST API to delete a pledge for the submitted
 *			project and current user.
 */
function deletePledge(jsonPledge){
	
	// Give the user a chance to bail out.
	var message = "Delete your pledge for project, " +
		jsonPledge.project_noSearch + 
		" and amount, $" + jsonPledge.amount + "?";
	if (!confirm(message)) {return;}
	var url = "/common/rest/pledge.php/pledge/" + jsonPledge.pledge_no.toString();
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			returnToTable(this.responseText) ;
		} 
	}
	xhttp.open("DELETE", url, true);  
	xhttp.send();
}


/*
 *	deletePledgeForUser() - Find the pledge for the current user.
 */
function deletePledgeForUser(project_no) {

	var id = getCurrentUser() ;
	var url = "/common/rest/pledge.php/pledge" ;
	var queryStr = "?compare=strict&column[project_no]=" + project_no.toString() + 
					"&column[id]=" + id.toString();	
	url += queryStr;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4) {
			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);

			if (this.status == 200) {
				if (this.responseText == "") {
					alert ("You don't have a pledge for this project.");
					return;
				}
				
				// Otherwise, assume we have an existing pledge.
				var jsonObj = JSON.parse(this.responseText);
				var jsonData = jsonObj[1][0];
				deletePledge(jsonData);
			}
			else {
				
			}
		}
	}
	xhttp.open("GET", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send();
}


/*
 *	editPledge - User request to edit an existing instance of the resource.
 */
function editPledge(responseText, formId="pledgeForm"){
	
	/********************* NOTE: Called from AJAX! ******************
	/	This procedure is placed here because it closely resembles the 
	/	other procedures in this section.  It preps the form for editing.
	/	Unlike most of these routines, however, this procedure is 
	/	called from getPledge()--which is another AJAX call (below).
	/****************************************************************/

	// Prepare the form to edit an instance of the resource.
	prepPledgeForm("pledgeForm");
	
	// Clear the form.
	clearForm(formId);
	
	// Load the form with the selected instance.
	loadFormFromJSON(formId, responseText);
		
	// Show the modal form.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('show');
}


function getCurrentUser() {

	// Return the currently logged in user's id.
	var id = 0;
	
	// Make sure we have a valid user.
	if (loggedin()) {
		var user = auth['id'];
		id = user.toString();
		return id ;
	}
/* 	else {
		alert("You need to log in to submit or modify a pledge.");
		logout();
	}
 */
}


/*
 *	getPledge() - User request to edit an instance by a click on the pledge control.
 *  			GetPledge calls the REST API to read an instance from the 
 *				database.
 */
function getPledge(project_no, projectname, estimated_cost) {

	// Capture the currently logged in user's id.
	var id = getCurrentUser() ;
	var url = "/common/rest/pledge.php/pledge" ;
	var queryStr = "?compare=strict&column[project_no]=" + project_no.toString() + 
					"&column[id]=" + id.toString();	
	url += queryStr;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4 ) {
					
			// Capture the response reguardless of the status.
			var jsonStr = this.responseText
			$("#restResponse").html(jsonStr);

			// A successful response may return an empty string, which indicates
			// that an existing pledge could not be found.
			if (this.status == 200) {
				if (jsonStr == "") { 
					// Generate a json object to load the form.
					jsonStr = jsonForNewPledge(project_no, projectname, estimated_cost);
				}
				
				// Edit the pledge whether new or existing.
				editPledge(jsonStr, "pledgeForm");
			}
		}
	}
	xhttp.open("GET", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send();
}


/*
 *	jsonForNewPledge -- Create a JSON object to feed the form.
 */
function jsonForNewPledge(project_no, projectname, estimated_cost) {

	// Capture the current user's 
	var id = getCurrentUser();
	var user_id = auth['user_id'];

	// Construct the json object.
	var jsonData = '[{"count":0},' + 
				'[{"pledge_no":0' +
				',"id":' + id + 
				',"idSearch":"' + user_id +
				'","project_no":' + project_no +
				',"project_noSearch":"' + projectname +
				'","estimated_cost":' + estimated_cost +
				',"amount":0.00' +
				',"comment":""' +
				'}]]';

	return jsonData;
}


/*
 *	prepPledgeForm - Sets the modal form up for editing an existing pledge.
 *					(Tailored from setFormToEditMode().)
 */

function prepPledgeForm(formId="pledgeForm") {

	// Set the form's action to edit an instance of the resource.
	var jqFormId = "#" + formId;
	var jqFormDescription = jqFormId + "Description";
	var buttonId = jqFormId + "Continue" ; 
	$(jqFormDescription).html("Edit your pledge.  Click below to save change.");
	$(buttonId).off("click");		// Remove previous click event handler.
	$(buttonId).html("Save Changes");
	$(buttonId).click(function (){
		savePledge(formId);
	});
}


function savePledge() {

	// Capture the form data.
	var jsonStr = jsonFromForm("pledgeForm");
	var jsonData = JSON.parse(jsonStr);
	var requestMethod = "PUT";

	if (jsonData.amount == 0) {
		alert ("You must enter an amount before your pledge can be saved.") ;
		return ;
	}

	// Set the REST request method based on whether a current pledge number is
	//	available.
	if (jsonData.pledge_no > 0) { requestMethod = "PATCH"; }

	var restURI = "/common/rest/pledge.php/pledge?" ;
//	alert ("Updating pledge: " + jsonData.pledge_no + " with: " + jsonStr);

	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
			
			if (this.status !== 200) {
				alert ("Sorry, but your pledge could not be saved.  Please check the REST response for more information.");
			}
			returnToTable(this.responseText, "pledgeForm");
		} 
	}
	xhttp.open(requestMethod, restURI, true);
//	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonStr);

}


