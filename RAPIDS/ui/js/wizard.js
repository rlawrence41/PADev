/*

	Wizard.js are javascript procedures used to execute a transaction wizard.
	
	A transaction wizard groups the admin applications for the resources needed to
	complete a transaction.  An example is a customer order.  It has a parent resource
	of orders, but requires interaction with contact, orderItem, and receipt to
	complete a transaction.
	
	These procedures are similar to those found in pageAction.js, but are unique to
	executing the transaction wizard.
	
	In this development, I have found it useful to distinguish between the 
	"transaction" and the "parent" resource.  Using the customer order example, the 
	customerOrder resource is the "transaction" resource.  The "parent" resource for 
	the customerOrder transaction is "orders".

 */


	// launchStep() makes a request to the transaction wizard for the submitted step, 
	//	and key value.
	function launchStep(parentKey, stepName, keyFieldName, value) {

		// The originating application is now the URL to refresh the table.
		var transResource = getTransactionResource();		// Pull the transaction resource from the path.
		var url = location.protocol + "//"  + window.location.hostname ;	// Path to this application
		url += "/" + transResource ;						// the wizard folder
		url += "/" + transResource + "Wizard.php" ;			// Call the wizard.
		url += "?parentKey=" + parentKey ;					// Pass the key value of the trans transaction.
		url += "&step=" + stepName ;						// Pass the step name.

		var filterStr = "";
		if (value > 0) {
			filterStr = "column[" + keyFieldName + "]=" + value.toString();
		}

		if (filterStr.length > 0) {
			url += "&" + filterStr;		// Add the filter to the query string.
		}

		var messageStr = "You have launched wizard step: " + stepName +
					" filtered by, " + filterStr + ".\n" +
					" resulting URL: " + url ;

//		alert(messageStr) ;

		location.assign(url);

	}


	// launchStepWithParameters() does the same thing as launchStep() but allows
	// multiple parameters to be specified in an object.
	function launchStepWithParameters(parentKey, stepName, parameters) {

		// The originating application is now the URL to refresh the table.
		var transResource = getTransactionResource();		// Pull the transaction resource from the path.
		var url = location.protocol + "//"  + window.location.hostname ;	// Path to this application
		url += "/" + transResource ;						// the wizard folder
		url += "/" + transResource + "Wizard.php" ;			// Call the wizard.
		url += "?parentKey=" + parentKey ;					// Pass the key value of the trans transaction.
		url += "&step=" + stepName ;						// Pass the step name.

		var filterStr = "";
		
		// The list of parameters will be an object...
		if(typeof(parameters)=="object"){ 
			for (let key in parameters) {
				// Add the parameters separater.
				if (filterStr.length > 0) filterStr += "&";
				// Add the new parameter to the columns[] collection.
				filterStr += "column[" + key  + "]=" + parameters[key];
			}
		}

		url += "&" + filterStr ;					// Pass the parameters.

		messageStr = "You have launched wizard step: " + stepName +
					" filtered by, " + filterStr + ".\n" +
					" resulting URL: " + url ;
		
//		alert(messageStr) ;

		// Redirect to the new URL.
		location.assign(url);

	}

	
	
	// exitStep() -- This does a cleanup for the wizard step.  
	//		For example, it is necessary to release the step resource table from the 
	//		$_SESSION[] object.
	// 		Control is returned to the transaction report.
	function exitStep(parentKey=0, stepName=""){
	
		var transResource = getTransactionResource();		// Pull the transaction resource from the path.
		var url = location.protocol + "//"  + window.location.hostname ;	// Path to this application
		url += "/common/exitStep.php" 
		url += "?parentKey=" + parentKey ;					// Pass the key value of the trans transaction.
		url += "&step=" + stepName ;						// Pass the step name.
		

		messageStr = "You are exiting wizard step: " + stepName +
					" resulting URL: " + url ;

//		alert(messageStr) ;


		// Instantiate the request.
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {

			if (this.readyState == 4 && this.status == 200) {

					// Return to the transaction report.
					reportTransaction(parentKey);
			}
		}
		xhttp.open("GET", url, true);
		xhttp.send();
	}
	
	

	// A quick way to obtain the parent resource key.
	function getParentKey() {

		var transRecord = getSelected();
		return transRecord.id;

	}


/* 
	The getSelected() captures the previously selected transaction from the 
	hidden element with id="selectedRec".
	
 */
	function getSelected() {
		
		let text = document.getElementById("selectedRec").innerHTML;
		const transRecord = JSON.parse(text);
		
//		let message = "The id of the selected transaction is: " + record.id;
//		alert(message);
		
		return transRecord ;

	}


/*	
	Since the target resource is reassigned for every supported resource widget, we 
	need a simple way to capture the transaction resource.  It will be in the document path.
*/

function getTransactionResource() {
	
	var path = location.pathname;
	const pathArray = path.split("/");
	return pathArray[1];
	
}


/*
 	getTransaction - requests a transaction record from the REST 
	API.
 */
function getTransaction(transResource, keyValue){
	
	var url = location.protocol + "//"  + window.location.hostname ;
	url += "/" + transResource + "/rest/" + transResource + ".php/" + transResource
	url += "?limit=" + limit;

	filterStr = '&column[id]=' + keyValue;
	url += filterStr ;
	
//	alert("GetTransaction is calling: " + url);
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4) { 
			// Save the response to an element to register the change.
//			$("#restResponse").html(this.responseText);

			if (this.status == 200) {
				// Save the transaction record to a variable for later reference.
			 	try {jsonObj = JSON.parse(this.responseText);}
				catch (err){
					alert(err.name + ":  " + err.message + " from the following REST response...\n" + this.responseText);
					return;
				}

				// jsonObj[0] now contains summary information, including the record count.
				// The data is in jsonObj[1];
				transObj = jsonObj[1][0];
//				alert("transaction Record: " + JSON.stringify(transObj)) ;
			}
		}
	}
	xhttp.open("GET", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send();
}


/*
 	ReportTransaction() - Same as Report(), but retains the context of the 
		transaction wizard.
		
		Also, this report may be launched by an application for another resource.
		
  */
function reportTransaction(keyValue=0, resource=""){

	// If the resource has not been provided, assume the current application folder.
	var transResource = "";
	if (resource != "") transResource = resource;
	else transResource = getTransactionResource();

	// The originating application holds the URL for the report.
	var url = location.protocol + "//"  + window.location.hostname ;
	url += "/" + transResource + "/" + transResource + "Report.php";

	// If the keyValue has been supplied, use it for the filter condition.
	// Otherwise, get the currently selected transaction id.
	if (keyValue == 0) {

		// Capture the selected transaction.
		var record = getSelected();
		if (record.id > 0) keyValue = record.id;
	}
		

	// Filter for the selected transaction.
	var filterStr = "";
	if (keyValue > 0) {
		filterStr = "?column[id]=" + keyValue ; 
		url += filterStr ;
	}

	// Selecting a single transaction, so no need for sort by or limit clauses.

	url = encodeURI(url) ;
	
	// Request the URL in a new tab.
	window.location.assign(url);

}



/*******************************************************************************
 *
 *	The following procedures are CALLED FROM the above AJAX calls, upon a 
 *	successful response.  These functions must wait for the asynchronous 
 *	result from AJAX.
 *
 *******************************************************************************/

function returnToReport(responseText, formId="adminForm") {
	// This procedure is called at the succesful completion of operations
	// initiated by the modal form.
	
	// Save the response to an element to register the change.
	$("#restResponse").html(responseText);
	
	// Hide the modal form.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('hide');
	
	// Go back to the transaction report.
	reportTransaction();

}
