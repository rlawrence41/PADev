/*
 *	pageActions.js -- This procedure contains the javascript necessary to handle
 *					interactions with the user on most PubAssist components.
 *
 *	This module is included by a <script> link in templates/pageBootstrap.html.
 *
 *	The Page Process:
 *
 *	1)	The steady state is the page with the table presented.  This state will 
 *	have a “context” defined by the current filter, page number, and sort order.
 *
 *	2)	From here, the user can branch into any of several functions—-ALL of which 
 *	result in refreshing the table.
 *
 *		a)	Set a filter
 *		b)	Add an instance
 *		c)	Edit an instance
 *		d)	Delete an instance
 *		e)	Navigate to a new page number
 *		f)	Set a new sort order
 *
 *	3)	Features A through C above launch the modal form.  The form submission calls 
 *	an appropriate routine to affect the desired result for each feature.
 *
 *	4)	Features D through F above do NOT launch the form; BUT they perform an 
 *	operation that will most likely require a refresh of the table.
 *
 *	5)	Users will cycle through the above operations, until they exit the application.
 */

// Add the following jQuery event handlers.
$(document).ready(function(){			

	// Hide the REST Response element at the start.
	$("#restResponse").hide();
 
	// Toggle (Hide/Show) the REST API response.
	$("#viewResponse").click(function(){
		if ($("#restResponse").is(":visible")) {
			$("#restResponse").hide();
			$(this).html('<img src="/images/Show-Property-icon.png" title="Show response" />');
		}
		else {
			$("#restResponse").show();
			$(this).html('<img src="/images/Hide-Property-icon.png" title="Hide response" />');
		}
	});

	// Set the last page control.
	refreshLastPage();

	// Hide any search lists--since they will be empty.
	$(".searchList").hide();

	// Execute the "continue" button's click event when the user hits ENTER.
	
	
});


/*******************************************************************************
 *
 *	The following procedures are called when a control is clicked by the user
 *	to take some action that deviates from the steady state.  These routines
 *	will result in the launching of an AJAX call.  They are NOT the actual
 *	AJAX calls.
 *
 *		a)	Set a filter
 *		b)	Add a new instance
 *		c)	Edit an existing instance	NOTE: Called from AJAX!
 *		d)	Delete an instance
 *		e)	Navigate to a new page
 *		f)	Set a new sort order
 *******************************************************************************/


/*
 *	editInstance - User request to edit an existing instance of the resource.
 */
function editInstance(responseText, formId="adminForm"){
	
	/********************* NOTE: Called from AJAX! ******************
	/	This procedure is placed here because it closely resembles the 
	/	other procedures in this section.  It preps the form for editing.
	/	Unlike most of these routines, however, this procedure is 
	/	called from getInstance()--which is another AJAX call (below).
	/****************************************************************/

	// Save the response to an element to register the change.
	$("#restResponse").html(responseText, formId);
	
	// Prepare the form to edit an instance of the resource.
	setFormToEditMode(formId);
	
	// Clear the form.
	clearForm(formId);
	
	// Load the form with the selected instance.
	loadFormFromJSON(formId, responseText);
		
	// Show the modal form.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('show');
}


/*
 *	NewFilter - User requests a new filter by a click on the filter control.
 */
function newFilter(formId="adminForm"){

	// Prepare the form to collect a new filter condition.
	setFormToFilterMode(formId);

	// Show the modal form.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('show');

}


/*
 *	NewInstance - User requests to add a new instance by a click on the add control.
 */
function newInstance(formId="adminForm"){
	
	// Clear the form of any previous entries.
	clearForm(formId);
	
	// Prepare the form to add a new instance.
	setFormToAddMode(formId);

	// Show the modal form.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('show');
}


/*
 *	NewSearch - User requests to for an instance by a click on a launch control.
 *				(Generally a menu option.)
 */
function newSearch(formId="adminForm"){
	
	// Clear the form of any previous entries.
	clearForm(formId);
	
	// Prepare the form to add a new instance.
	setFormToSearchMode(formId);

	// Show the modal form.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('show');
}


/*
 *	downloadCSV - User request to download a CSV file containing records from
 *				the current context.  In other words, the filter condition and
 *				sort order apply to the records retrieved.  There is no change
 *				in the display for this procedure.
 */
function downloadCSV(){

	/********************* NOTE: this IS an AJAX call! ******************/
	
	// The originating application is now the URL to refresh the table.
	// var url = parentPath() ;  
	
	var url = "https://" + window.location.hostname ;
	url += "/downloadCSV.php?resource=" + resource ;

	if (filterStr.length > 0) {
		url += "&" + filterStr;			// Add the filter (i.e. column[]) to the query string.
	}

	if (sortBy.length > 0) {
		url += "&sortby=" + sortBy;	// Add the sort order to the query string.
	}
	url = encodeURI(url) ;

	alert("Download CSV is calling: " + url);
	
	// Call the URL.
	window.location.assign(url);
	
}


/*
 *	Report - User request a report containing records from the current context.  
 *				In other words, the filter condition and
 *				sort order apply to the records retrieved.  
 */
function report(){

	// The originating application holds the URL for the report.
	// var url = parentPath() ;  
	
	var url = "https://" + window.location.hostname ;
	url += "/" + resource + "/" + resource + "Report.php?limit=" + limit;

	if (filterStr.length > 0) {
		url += "&" + filterStr;		// Add the filter (i.e. column[]) to the query string.
	}

	if (sortBy.length > 0) {
		url += "&sortby=" + sortBy;	// Add the sort order to the query string.
	}
	url = encodeURI(url) ;

//	alert("This will request the following: " + url);
	
	// Request the URL in a new tab.
	window.open(url, "_blank");

}


/*******************************************************************************
 *
 *	The following procedures call the REST API to handle the CRUD (Create, Read,
 *	Update, and Delete) operations for this application.
 *
 *	Note that ALL CRUD operation results will update the restResponse element.
 *
 *******************************************************************************/

/*
	addInstance() - calls the REST API to save a new instance.
 */
function addInstance(formId="adminForm") {

	var resourceURI = restURL;
	var jsonData;
	jsonData = jsonFromForm(formId);
//	alert ("Adding new " + resource + ":  " + jsonData);

	// Check for defaults here.
	
	// Add trace fields to the record.
	jsonData = addTraceFields(jsonData);

	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			returnToTable(this.responseText) ;
		} 
	}
	xhttp.open("PUT", resourceURI, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonData);
	
}


/* 
 *  deleteInstance(keyValue) – calls the REST API to delete an instance.
 */
function deleteInstance(keyValue){
	
	var resourceURI = restURL + "/" + keyValue;
	var jsonData;
	
	// Give the user a chance to bail out.
	if (!confirm("Delete " + resource + ":  " + keyValue)) {return;}
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			returnToTable(this.responseText) ;
		} 
	}
	xhttp.open("DELETE", resourceURI, true);  
	xhttp.send();
}


/*
 *	getInstance - User request to edit an instance by a click on the edit control.
 *  			GetInstance calls the REST API to read an instance from the 
 *				database.
 */
function getInstance(keyValue){

	/********************* NOTE: this IS an AJAX call! ******************
	// Unlike most of these AJAX calls, this procedure is called directly
	// from a user click on a control in the table.  This is the first
	// step to editing an instance of the resource.
	/********************************************************************/
	
	var resourceURI = restURL + "/" + keyValue;
//	alert("GetInstance is calling: " + resourceURI);
	var jsonData;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4) { 

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
		
			if (this.status == 200) {
				editInstance(this.responseText);
			}
		} 
	}
	xhttp.open("GET", resourceURI, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send();
}

/*
 *	searchInstance() - uses the contents of the form as search criteria for an 
 *	instance of the resource.
 */
function searchInstance(formId="adminForm") {

	var resourceURI = restURL;
	var queryStr = queryStrFromForm(formId);
	resourceURI += "?" + queryStr;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
//		if (this.readyState == 4 && this.status == 200) {
		if (this.readyState == 4 ) {
			
			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);

			// Hide the modal form.
			var jqDivId = "#" + formId + "Div";
			$(jqDivId).modal('hide');

			if (this.status != 200) {alert(this.responseText);}
		}
	}
	xhttp.open("GET", resourceURI, true);
	xhttp.send();
	
}


/*
	updateInstance() - calls the REST API to update an instance in the database.
 */
function updateInstance(formId="adminForm") {

	var resourceURI = restURL;
	var jsonData;
	jsonData = jsonFromForm(formId);

	// Add trace fields to the record.
	jsonData = addTraceFields(jsonData);
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			if (this.status == 200) {returnToTable(this.responseText) ;}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
		}
	}
	xhttp.open("PATCH", resourceURI, true);
//	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonData);
	
}


/*******************************************************************************
 *
 *	The following procedures are CALLED FROM the above AJAX calls, upon a 
 *	successful response.  These functions must wait for the asynchronous 
 *	result from AJAX.
 *
 *******************************************************************************/

function returnToTable(responseText, formId="adminForm") {
	// This procedure is called at the succesful completion of operations
	// initiated by the modal form.
	
	// Save the response to an element to register the change.
	$("#restResponse").html(responseText);
	
	// Hide the modal form.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('hide');
	
	// Refresh the table.
	refreshTable();

}


/*******************************************************************************
 *
 *	Similar to other REST API calls, refreshTable() is an AJAX call, but to this 
 *	same application.  It causes records that satisfy the current context 
 *	(i.e. the filter, page, and sort order) to be gathered.
 *
 *******************************************************************************/

/*
	refreshTable() - An AJAX request to refresh the table with potentially new data.
 */
function refreshTable() {
	
	// The originating application is now the URL to refresh the table.
//	var url = parentPath() +  "/" + resource + "Table.php?";
	var url = "https://" + window.location.hostname ;
	url += "/" + resource + "/" + resource + "Table.php";
	
	var queryStr = queryStrFromContext();
//	var queryStr = window.location.search;			// Retain the querystring.
	if (queryStr.length > 0) url += queryStr;
	
//	alert("Refresh Table with: " + url);

	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState == 4 &&	this.status == 200) {
		
//		alert("Response: " + this.responseText);
		
			// Save the response to the table division on the page.
			$("#tableAdmin").html(this.responseText);	// Update the table.

			// Refresh the pageNav control.
			refreshCurrentPage(pageNo);
			refreshLastPage();

		}
	}
	
	xhttp.open("GET", url, true);  //"True" indicates asynchronous!
	xhttp.send();

}


/*******************************************************************************
 *
 *	The following procedures set up the modal form to add or edit an instance
 *	of the resource, or to set a filter condition for the table.  Note that
 *	each setup will change the function of the submit button (id="continue")  
 *	on the form.
 *
 *******************************************************************************/

/*
 *	setFormToAddMode - Sets the modal form up for adding a new instance.
 */
function setFormToAddMode(formId="adminForm") {

	// Set the form's action to add a new instance of the resource.
	var jqFormId = "#" + formId;
	var jqFormDescription = jqFormId + "Description";
	var jqButtonId = jqFormId + "Continue" ; 
	$(jqFormDescription).html("Add a new " + resource);
	$(jqButtonId).off("click");		// Remove previous click event handler.
	$(jqButtonId).html("Save New");
	$(jqButtonId).click(function (){
		addInstance(formId);
	});

	// Set the "Continue" button to execute on ENTER.
	setPrimaryButton(jqFormId, jqButtonId);
}


/*
 *	setFormToEditMode - Sets the modal form up for editing an existing instance.
 */
function setFormToEditMode(formId="adminForm") {

	// Set the form's action to edit an instance of the resource.
	var jqFormId = "#" + formId;
	var jqFormDescription = jqFormId + "Description";
	var jqButtonId = jqFormId + "Continue" ; 
	$(jqFormDescription).html("Edit this instance.  Click below to save change.");
	$(jqButtonId).off("click");		// Remove previous click event handler.
	$(jqButtonId).html("Save Changes");
	$(jqButtonId).click(function (){
		updateInstance(formId);
	});

	// Set the "Continue" button to execute on ENTER.
	setPrimaryButton(jqFormId, jqButtonId);
}


/*
 *	setFormToFilterMode - Sets the modal form up for collecting selection criteria to 
 *	set a filter.
 */
function setFormToFilterMode(formId="adminForm") {

	// Set the form's action collect a filter condition.
	var jqFormId = "#" + formId;
	var jqFormDescription = jqFormId + "Description";
	var jqButtonId = jqFormId + "Continue" ; 
	$(jqFormDescription).html("Use form fields to filter records presented in the table.");
	$(jqButtonId).off("click");		// Remove previous click event handler.
	$(jqButtonId).html("Set Filter");
	$(".form-control").val("");			// Clear all form controls.
	$(jqButtonId).click(function (){
		setFilter(formId);
	});

	// Set the "Continue" button to execute on ENTER.
	setPrimaryButton(jqFormId, jqButtonId);
}


/*
 *	setFormToSearchMode - Sets the modal form up for collecting selection criteria for 
 *	a search.
 */
function setFormToSearchMode(formId="adminForm") {

	// Set the form's action collect a filter condition.
	var jqFormId = "#" + formId;
	var jqFormDescription = jqFormId + "Description";
	var jqButtonId = jqFormId + "Continue" ; 
	$(jqFormDescription).html("Use form fields to filter records presented in the table.");
	$(jqButtonId).off("click");		// Remove previous click event handler.
	$(jqButtonId).html("Search");
	$(".form-control").val("");			// Clear all form controls.
	$(jqButtonId).click(function (){
		searchInstance(formId);
	});
	
	// Set the "Continue" button to execute on ENTER.
	setPrimaryButton(jqFormId, jqButtonId);
}


// Set the "Continue" (primary) button to fire when the user hits ENTER.
function setPrimaryButton(jqFormId, jqButtonId){

	$(jqFormId).keypress(function (e) {
	 var key = e.which;
	 if(key == 13)  // the enter key code
	  {

		// Force blur on the currently focused input
		$(':focus').trigger('blur');

		$(jqButtonId).click();
		return false;  
	  }
	});   	
}


/*******************************************************************************
 *
 *	The following procedures change the current context (filter, page, or sort 
 *	order).  These too are generally called from a click on a control and each
 *	results in a table refresh.
 *
 *******************************************************************************/

/*
 *	clearFilter - Clears the current filter condition.
 */
function clearFilter(formId="adminForm") {
	
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('hide');			// Hide the modal form.
	filterStr = "";						// filterStr is global to persist for other events.
	pageNo = 1;							// A new filter should reset the page.												
	$('#filterStr').html("No filter");	// Show the filter string on the page.

//	alert("Filter has been cleared.");	
	refreshTable();
}


/*
 *	clearSortBy() - This procedure clears the Sort By attribute of the context
 *				and causes the table to be sorted by its original order.
 */
function clearSortBy(){
	sortBy = "";
	pageNo = 1;										// A new sort should reset the page.												
	refreshTable();
	$("#sortBy").text("No Sort Order");			// Show sort order in clear button.
}


/*
 *	setFilter - Sets a new filter condition based on the selection criteria 
 *				submitted in the form.
 */
function setFilter(formId="adminForm") {
	
	filterStr = queryStrFromForm(formId);	// "filterStr" is global variable to 
											//	persist for other events.
	if (persistentFilter > "") {			// If a persistent filter has been 
		filterStr += "&" + persistentFilter ; // provided, be sure to add it to the 
											// filterStr.
	}
	pageNo = 1;								// A new filter should reset the page.												
	$('#filterStr').text(filterStr);		// Show the filter string on the page.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('hide'); 				// Hide the modal form.
	refreshTable();

}


/*
 *	setSortBy(fieldList) - This procedure sets the Sort By attribute of the context 
 *				and causes the table to be sorted by the new expression.
 */
 function setSortBy(fieldName){
	
	// Make sure we have a field.
	if (fieldName.length > 0){
		// Make sure the field is not already in the sort order.
		if (sortBy.search(fieldName) < 0){
			if (sortBy.length > 0){sortBy += ","; }
			sortBy += fieldName; 
//			alert("Sorting By: " + sortBy);
			$("#sortBy").text(sortBy);		// Show sort order.
			pageNo = 1;						// A new sort should reset the page.												
			refreshTable();		 
		}
	}
 }


/*
	queryStrFromContext() - the context has already been manifested in Javascript variables.
 */
function queryStrFromContext() {

    var queryStr = "";

	// If a persistent filter has been provided, be sure to add it first.
	if (persistentFilter > "") {			
		queryStr = persistentFilter ; 
	}

	// The filter string was updated by the admin form.  It should already be stuctured for
	// the column[] array.
	if (filterStr.length > 0) {
		if (queryStr.length > 0) queryStr += "&" ;
		queryStr += filterStr;		// Add the filter to the query string.
	}

	// Add the page number to the query string.
	if (pageNo > 1) {
		if (queryStr.length > 0) queryStr += "&" ;
		queryStr += "page=" + pageNo;
	}

	// Add the sort order to the query string.	
	if (sortBy.length > 0) {
		if (queryStr.length > 0) queryStr += "&" ;
		queryStr += "&sortBy=" + sortBy;	
	}

	// Precede the query string with a question mark.
	if (queryStr.length > 0) queryStr = "?" + queryStr;

    return queryStr;
}


/*******************************************************************************
 *
 *	The following procedures move data to or gather data from the modal form.
 *
 *******************************************************************************/

/* 
 *  clearForm() – This function will set values of all form elements to an
 *					empty string.
 */
function clearForm(formId="adminForm") {
	
	var form = document.getElementById(formId);
	var inputList = form.getElementsByClassName("form-control");
    var i, fieldName, fieldValue;
    for (i = 0; i < inputList.length ;i++) {inputList[i].value = "";}
}


/* 
    LoadFormFromJSON() – will load the json elements into the appropriate elements 
    of the form on the page.
 */
function loadFormFromJSON(formId="adminForm", restResponse) {

	var jsonObj, jsonData ;
 	try {jsonObj = JSON.parse(restResponse);}
	catch (err){
		alert(err.name + ":  " + err.message + " from the following REST response...\n" + restResponse);
		return;
	}

	// jsonObj[0] now contains summary information, including the record count.
	// The data is in jsonObj[1];
	jsonData = jsonObj[1][0];

	// Can no longer count on input elements identified by field name.
	// NOTE: The "form-control" class is used to gather the form elements for loading.
	//		The "data-set" class is used to gather form elements for SAVING.
	var formElement, inputList, inputElement, fieldName, fieldVal, report = "";
	formElement = document.getElementById(formId);
	inputList = formElement.getElementsByClassName("form-control");
//	inputList = formElement.getElementsByClassName("data-set");
	for (i in inputList){
		fieldName = getFieldName(inputList[i]);
		if (fieldName == ""){break;}

		fieldVal = jsonData[fieldName];
		report += assignValue(inputList[i], fieldVal);	

		// Check for an onChange event handler for the element and trigger.
		if (inputList[i].onchange) {
			inputList[i].onchange(inputList[i]);
		}
		
	}
	
}




/* 
    jsonFromForm(formId) – This function will return a json object containing the current 
	values of form elements.
 */
function jsonFromForm(formId="adminForm") {
	
	// Bootstrap input elements are all in class "form-control".
	// I added the "data-set" class name to the fields in the database.
	// Capture the list of input elements for the form.
	var myform = document.getElementById(formId);
	
	// NOTE: The "form-control" class is used to gather the form elements for loading.
	//		The "data-set" class is used to gather form elements for SAVING.
	// 		In other words, if a form element is NOT in the "data-set" class, then
	//		it should not be included in the JSON object for updating.
	var inputList = myform.getElementsByClassName("data-set");

    var jsonStr = "";
    var i, fieldName, fieldValue;
	var inputName = "";
    for (i = 0; i < inputList.length ;i++) {
		
		//fieldName = inputList[i].id;

		// Change 09/19/2019 - Make sure that each field is a column in the 
		// 					target database table.  Valid fields will have
		//					the name attribute set to "column[{fieldName}]".
		inputName = inputList[i].getAttribute("name"); 
		// getAttribute seems to be returning NULL instead of an empty string.
		if (inputName !== null){
			if (inputName.startsWith("column[")){
				
				fieldName = getFieldName(inputList[i]);
				fieldValue = formatValue(inputList[i]);
				
/* 				// Checkboxes must be handled differently.
				if (inputList[i].type == "checkbox") {

					// MySQL requires saving a boolean as a number.
					fieldValue = inputList[i].checked ? 1 : 0;
//					alert("Input element " + inputName + " is " + fieldValue);
				}
				else {
					fieldValue = addQuotes(inputList[i].value);			
				} */

				if (jsonStr.length > 0) {jsonStr+=", ";}
				jsonStr += addQuotes(fieldName) + ":" + fieldValue;
			}
//			else {alert("Input element name is: " + inputName);}
		}
//		else {alert("Name attribute for element " + fieldName + " is NULL!");}
    }
	if (jsonStr.length > 0) {jsonStr = "{" + jsonStr + "}";}
	
//	alert("jsonFromForm: " + jsonStr);
    return jsonStr;
}


/*
	queryStrFromForm() - This function gathers <input> element values into a query string.
 */
function queryStrFromForm(formId="adminForm") {

	// Bootstrap input elements are all in class "form-control".  
	// Capture the list of form controls.
	var myform = document.getElementById(formId);
//	var inputList = myform.getElementsByClassName("form-control");
	var inputList = myform.getElementsByClassName("data-set");
    var queryStr = "";
    var i, fieldName, fieldValue;
    for (i = 0; i < inputList.length ;i++) {
		//fieldName = inputList[i].id;
		fieldName = getFieldName(inputList[i]);
		fieldValue = inputList[i].value;
		// Checkboxes must be handled differently.
		if (inputList[i].type == "checkbox") {
			if (inputList[i].checked) {
				if (queryStr.length > 1) {queryStr+="&";}
				queryStr += "column[" + fieldName + "]=1" ;
			}
		}
		else {
		// Add the field value to the query string.
			if (fieldValue.length > 0) {

				if (queryStr.length > 1) {queryStr+="&";}
				// Note that queryStr does NOT require quotes around column indices or 
				// field values.
				queryStr += "column[" + fieldName + "]=" + fieldValue;
			}
		}
    }
	
    return queryStr;
}


/* 
    stringFromJSON() – will put JSON values in a string.
 */
function stringFromJSON(jsonData) {

	var jsonObj, jsonSummary, jsonRecord ;
 	try {jsonObj = JSON.parse(jsonData);}
	catch (err){
		alert(err.name + ":  " + err.message + " from the following JSON data...\n" + jsonData);
		return;
	}

	// jsonObj[0] now contains summary information, including the record count.
	// The data is in jsonObj[1];
	// Reminder that jsonObj[] is a collection of objects.  So to get the record data
	// I need to refer to jsonObj[1][0].
	jsonRecord = jsonObj[1][0];
	let valueStr = "";
	for (fieldName in jsonRecord) {

		if (valueStr > "") valueStr += ", " ;
		valueStr += jsonRecord[fieldName]  ;
	
	}
	return valueStr;

}


/************************ General Purpose ***********************************/

/*
	addQuotes(inStr) - Returns the submitted inStr surrounded by double quotes.  
	Double quotes are necessary for JSON formatting.
	
 */
function addQuotes(inStr) {
	
	var outStr = '"' + inStr + '"';
	outStr = outStr.replaceAll("\n","\\n");
	outStr = outStr.replaceAll("\r","\\r");
	return outStr;
}


/*
	The trace fields track who has made the latest update to the record.
 */
function addTraceFields(jsonStr){
	
	// Make sure the trace field exists in the jsonData before attempting to update.
	jsonObj = JSON.parse(jsonStr);

	// The current user should be in variable "auth".
	if (jsonObj.hasOwnProperty("updatedBy")) {
		jsonObj.updatedBy = auth.user_id;
	}
	
	if (jsonObj.hasOwnProperty("userNo")) {
		jsonObj.userNo = auth.id;
	}
	
	if (jsonObj.hasOwnProperty("lastUpdated")) {
		jsonObj.lastUpdated = sqlNow();
	}
	
	var jsonReturnStr = JSON.stringify(jsonObj);
	return jsonReturnStr
	
}


/*
	Assign a value to an input element based on the type.
 */
function assignValue(formElement, fieldVal) {

	var id = formElement.getAttribute('id');
	var type = formElement.type;
	var eol = "\n";
	var txt = id + " = " + fieldVal + ", Type: " + type + eol;
//	alert(txt);
	switch(type) {

		case "checkbox":
			
			if (fieldVal == null) { break; } 
			// Check the value for either "1" or "YES" to set the checked attribute.
			// The boolean value may be altered in rest/templace/<resource>_GET.sql.
			formElement.checked = ((fieldVal.toUpperCase() == "YES") || (fieldVal == "1"));
			formElement.value = Number(formElement.checked);
//			alert("Load Form: Setting " + formElement.id + " to " + formElement.value);
			break;
 
		case "date":
			formElement.value = formatDate(fieldVal);
			break;
		default:
			if (fieldVal !== undefined){formElement.value = fieldVal ;}
			else {formElement.value = "";}
	}
	
	return txt;
}


/*
 *	Branch to the login page.  Return when completed.
 */
 function branchToLogin() {
  location.replace("/login.php")
}


/*
	Convert a valid date expression to standard date format.
	From: https://stackoverflow.com/questions/23593052/format-javascript-date-to-yyyy-mm-dd
 */
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}


/*
 *	formatValue(inputElement) -- Format the value of an input element suitable
 *									for inclusion in a JSON string.
 */
function formatValue(inputElement){
	
	var varType = inputElement.type;
	var fieldValue = "";
	
	switch (varType) {

	  case "checkbox" :
		// MySQL requires saving a boolean as a number.
		fieldValue = inputElement.checked ? 1 : 0;
		break;

	  case "date":
		if (inputElement.value == ""){fieldValue = '"NULL"';}
		else {fieldValue = addQuotes(formatDate(inputElement.value));}
		break;

	  default:
		fieldValue = addQuotes(inputElement.value);
	}
	return fieldValue;
}



/*	getFieldName - extracts the field name from the name attribute of the
			submitted input element.
 */
function getFieldName(inputElement){
	
	var inputName, fieldName;
	
	// I'm getting submissions that are not real elements.
	try {inputName = inputElement.getAttribute('name');}
	catch(e){ return "";}
		
	fieldName = inputName.replace("column[", "");
	fieldName = fieldName.replace("]", "");
	return fieldName;
}


/*
 *	hideModalForm - Hides the submitted modal form.
 */ 
function hideModalForm(formId="loginForm"){

//	The following closes the form, but leaves the modal background.
//	var mdElementId = formId + "Div";
//	var mdElement = document.getElementById(mdElementId);
//	mdElement.style.display = "none";

	// Looks like Bootstrap REALLY wants us to use jQuery.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('hide');
	
}


function isElement(obj) {
  try {
    //Using W3 DOM2 (works for FF, Opera and Chrome)
    return obj instanceof HTMLElement;
  }
  catch(e){
    //Browsers not supporting W3 DOM2 don't have HTMLElement and
    //an exception is thrown and we end up here. Testing some
    //properties that all elements have (works on IE7)
    return (typeof obj==="object") &&
      (obj.nodeType===1) && (typeof obj.style === "object") &&
      (typeof obj.ownerDocument ==="object");
  }
}


/*
 *	parentPath - returns the parent folder path for the current page.
 */
 function parentPath() {

	var url = document.URL;
	var n, path ;

	// Be sure to remove the actual document name.
	
	n = url.lastIndexOf(".php");
	path = url.slice(0, n);
	n = path.lastIndexOf("/");
	path = path.slice(0, n);
	return path;
}


/*
 *	showModalForm - Shows the submitted modal form.
 */ 
function showModalForm(formId="loginForm"){

	// Looks like Bootstrap REALLY wants us to use jQuery.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('show');
	
}


/*
	SQLNow -- returns the current local date/time in SQL format.
 */
 function sqlNow(){

	const d = new Date();
	var dateStr = d.getFullYear();				//Get year as a four digit number
	var monthStr = '0' + (d.getMonth() + 1);	//Make sure month is 2 digits
	monthStr = monthStr.slice(-2);
	dateStr += "-" + monthStr;					//Get month as a number (0-11)
	dateStr += "-" + d.getDate();				//Get day as a number (1-31)
	dateStr += " " + d.getHours();				//Get hour (0-23)
	dateStr += ":" + d.getMinutes();			//Get minute (0-59)
	dateStr += ":" + d.getSeconds();			//Get second (0-59)
	return dateStr;
 }

 
/*
	sqlYearFromNow -- returns a local date/time one year from now in SQL format.
 */
 function sqlYearFromNow(){

	const d = new Date();
	var dateStr = d.getFullYear() + 1;				//Get year as a four digit number
	var monthStr = '0' + (d.getMonth() + 1);	//Make sure month is 2 digits
	monthStr = monthStr.slice(-2);
	dateStr += "-" + monthStr;					//Get month as a number (0-11)
	dateStr += "-" + (d.getDate() + 1);			//Get day as a number (1-31)
	dateStr += " " + d.getHours();				//Get hour (0-23)
	dateStr += ":" + d.getMinutes();			//Get minute (0-59)
	dateStr += ":" + d.getSeconds();			//Get second (0-59)
	return dateStr;
 }