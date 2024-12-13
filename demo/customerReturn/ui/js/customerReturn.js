/* 
	These procedures are unique to the customer return transaction application.
	
	In general, these procedure should respond to the following events:
	
	
				Event											User Action
    1. When adding a new transaction parent record.	User clicks on “Add” icon on Admin table.
    2. When adding a new instance to a supporting 	User clicks on “Add” icon in a Wizard Step.
		resource.
    3. When a supporting resource instance is 		User clicks on “Continue” button on edit form.
		updated.
    4. When a supporting resource instance is 		User clicks on “Select” icon in wizard step table.
		selected.
    5. When the transaction is completed.			User clicks on “Done” button on any wizard step.



 */

// Global variables for the customer return transaction...
var orderObj = null;
var customerObj = null;


/*	
	addItem(orderKey) adds a new item to the adminForm.
 */

function addItem(orderKey) {
	
	// Prepare the form.
	newInstance();
	
	// Add the order key to the form content.
	// The form input elements have unique ID's.  Need to capture the element by name.
	
	// Assign the order key.
	var elements = document.getElementsByName("column[orderKey]");
	elements[0].value = orderKey;
	
	// Default the order date.
	elements = document.getElementsByName("column[orderDate]");
	elements[0].value = sqlNow();
	
	// Set the discount for the customer if a profile is available.
	if (typeof customerObj == 'object') {
		if (customerObj.discount !== 0) {
			elements = document.getElementsByName("column[discount]");
			elements[0].value = customerObj.discount;
		}
	}
}


/*
	addParent() generates a template customer return record and adds it to the Orders 
	resource.  The transaction wizard is then called on the new order.
 */ 

function addParent(){

	var ownerNo= 1;
	var currentBatchNo = 3;
	// Sample:  {"id":"13", "contactId":"", "company":"COTTAGE INDUSTRY BOOKS", "namePrefix":"", "firstName":"U. OTTO", "midName":"", "lastName":"READMORE", "nameSuffix":"", "poAddr":"123 MAIN STREET", "courAddr":"U. OTTO READMORE\nTHIS IS AN ENTIRELY DIFFERENT ADDRESS FOR NON USPS COURIER SERVICES.", "city":"ANYTOWN", "stateAbbr":"NY", "country":"", "zipCode":"12345", "munAbbr":"", "billTo":"", "phone":"(866) 555-1212", "phone2":"", "email":"UOTTO@DEMOTITLES.COM", "webUrl":"", "webservice":"", "fedIdNo":"", "san":"", "pubnetId":"", "buyerId":"", "sellerId":"", "lExclude":0, "biography":"", "portrait":"", "comment":"", "password":"", "lAuthor":1, "lCustomer":1, "lMailList":0, "lSalesRep":0, "lSupplier":0, "lWarehouse":0, "lEmployee":0, "lApproved":0, "updatedBy":"RLAWRENCE", "userNo":"1", "lastUpdated":"2024-05-21 13:42:57"}
	var jsonData = {	"updatedBy":auth.user_id, 
						"userNo":auth.id, 
						"lastUpdated":sqlNow()
					}
											
	var jsonStr = JSON.stringify(jsonData);
	alert("About to update an orders record with:  \n" + jsonStr) ;

	var url = location.protocol + "//"  + window.location.hostname + "/customerReturn/rest/customerReturn.php" ;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			$("#restResponse").html(this.responseText);
			if (this.status == 200) {
				refreshTable();
			}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
		}
	}
	xhttp.open("PUT", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonStr);
	
}


/*
	callReceipt() -- branches to the receiptDetail wizard.  It assumes that a receipt
		record exists for the submitted orderKey.
 */
function callReceipt(orderKey){
	
//	alert("About to launch a new receipt for order key, " + orderKey + " for $" + amount + ".");

	//Sample URL: "https://dev.pubassist.com/receiptDetail/receiptDetailReport.php?column[orderKey]=42"
	var url = location.protocol + "//"  + window.location.hostname + 
		"/receiptDetail/receiptDetailReport.php?column[orderKey]=" +
		orderKey;
	
	window.open(url, '_blank');

}



/*	getCustomerProfile() -- looks up the customer profile to get defaults  
 */
function getCustomerProfile(customerNo){
	
	// 	Sample REST url:
	//	https://dev.pubassist.com/customer/rest/customer.php/customer/3
	
	var url = location.protocol + "//" + location.hostname ;
	url += "/customer/rest/customer.php/customer/" + customerNo;
	
	var jsonData;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4) { 

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
		
			if (this.status == 200) {
			 	try {customerObj = JSON.parse(this.responseText);}
				catch (err){
//					alert(err.name + ":  " + err.message + " from the following REST response...\n" + restResponse);
					customerObj = "";
					return;
				}

			}
		} 
	}
	xhttp.open("GET", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send();
	
}



/*
	getOrder() retrieves the parent customer return record via the REST API.
 */

function getOrder(orderKey){
	
	// 	Sample REST url:
	//	https://dev.pubassist.com/orders/rest/orders.php/orders/3
	
	var url = location.protocol + "//" + location.hostname ;
	url += "/orders/rest/orders.php/orders/" + orderKey;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4) { 

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
		
			if (this.status == 200) {
				orderObj = JSON.parse(this.responseText);
			}
		} 
	}
	xhttp.open("GET", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send();

}

/*
	postProcessCustomerReturn() kicks off the post-process for the transaction via the REST API.

	The post-process is implemented through a POST HTTP request and is implemented
	as a stored procedure in the database.
 */

function postProcessCustomerReturn(orderKey){
	
	// 	Sample REST url:
	//	https://dev.pubassist.com/orders/rest/orders.php/orders/3
	
	var url = location.protocol + "//" + location.hostname ;
	url += "/customerReturn/rest/customerReturn.php/customerReturn/" + orderKey;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4) { 
		
			if (this.status == 200) {
				alert("This customer return has been successfully processed.");
				location.assign("customerReturnAdmin.php");
			}
			else {
				alert("There was a problem processing this customer return: \n" +
					this.responseText);
			}
		} 
	}

	xhttp.open("POST", url, true);
	xhttp.send();

}


/*
	postReceiptForOrder() - causes a receipt to be logged for the submitted order.
	
	NOTE: To date, this AJAX procedure makes use of the HTTP PATCH method.  
	This procedure calls a unique stored procedure to insure the existence of a 
	receipt record for the orderKey submitted.
	
	This procedure is identical to a function of the same name in customerOrder.js.
	The difference is that the amount parameter will be submitted as a negative.
	
 */
function postReceiptForOrder(orderKey, customerNo, amount, status){
	
	// Derive the receipt rest URL from this transaction application's restURL. 
	var receiptRestUri = restURL.replace(/customerReturn/g,"receiptDetail");
	receiptRestUri += "?column[orderKey]=" + orderKey;

	// Load the parameters into JSON for transport.
	var jsonData = {	"orderKey":orderKey,
						"customerNo":customerNo, 
						"amount":amount
					}	
	var jsonStr = JSON.stringify(jsonData);
//	alert ("Posting a new receipt:  " + jsonStr);
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//returnToTable(this.responseText) ;
			callReceipt(orderKey);
		} 
	}
	xhttp.open("PATCH", receiptRestUri, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonStr);
	
}


/*
	updateBillTo() updates the orders record with the bill to contact id
	as well as attributes from the customer profile.

 */
function updateBillTo(customerNo=0) {
		
	var url = location.protocol + "//"  + window.location.hostname + 
				"/customerReturn/saveBillTo.php" ;

	// Add the parent key value from the customer return transaction.
	var orderKey = getParentKey();
	url += "?order=" + orderKey;
	
	// Add the selected contact ID.
	url += "&billTo=" + customerNo;

	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			$("#restResponse").html(this.responseText);
			if (this.status == 200) {
				alert(this.responseText);
				reportTransaction(orderKey);
			}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
		}
	}
	xhttp.open("GET", url, true);
	xhttp.send();

}


/*
	updateCustomerReturn() updates the orders record based on changes to any of
	the supporting resources.

 */
function updateCustomerReturn(orderKey=0) {
		
	var url = location.protocol + "//"  + window.location.hostname + 
				"/customerReturn/saveCustomerReturn.php" ;

	// Add the parent key value from the customer return transaction.
	url += "?order=" + orderKey;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			$("#restResponse").html(this.responseText);
			if (this.status == 200) {
				alert(this.responseText);
				reportTransaction(orderKey);
			}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
		}
	}
	xhttp.open("GET", url, true);
	xhttp.send();

}


/*
	updateSalesRep() updates the orders record with the ship to contact id.
 */
function updateSalesRep(salesRepNo=0) {

	// I don't need the whole orders record to execute an update.
	// I do need the parent key value, and the id of the selected contact.
		
	// The parent key value is in the transaction.
	var orderKey = getParentKey();

	var jsonData = {	"id":orderKey,
						"salesRepNo": salesRepNo, 
						"updatedBy": auth.user_id, 
						"userNo": auth.id, 
						"lastUpdated":sqlNow()
					}
	var jsonStr = JSON.stringify(jsonData);
	alert("About to update an orders record with:  \n" + jsonStr) ;
	updateParent(orderKey, jsonStr);

}


/*
	updateShipTo() updates the orders record with the ship to contact id.
	The shipping address must also be assembled--based on the courier service.  
	This is a bit more complex than a simple update to a field.  So, it makes 
	more sense to do this on the server rather than from the client.  
	
	Thus the introduction of saveShipTo.php in the customerReturn application.
 */
function updateShipTo(shipToNo=0) {

	// I don't need the whole orders record to execute an update.
	// I do need the parent key value, and the id of the selected contact.
		
	var url = location.protocol + "//"  + window.location.hostname + 
				"/customerReturn/saveShipTo.php" ;

	// Add the parent key value from the customer return transaction.
	var orderKey = getParentKey();
	url += "?order=" + orderKey;
	
	// Add the selected contact ID.
	url += "&shipTo=" + shipToNo;

	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			$("#restResponse").html(this.responseText);
			if (this.status == 200) {
				alert(this.responseText);
				reportTransaction(orderKey);
			}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
		}
	}
	xhttp.open("GET", url, true);
	xhttp.send();

}


/*
	updateSupplier() updates the orders record with the supplier contact id.
 */
function updateSupplier(supplierNo=0) {

	// I don't need the whole orders record to execute an update.
	// I do need the parent key value, and the id of the selected contact.
		
	// The parent key value is in the transaction.
	var orderKey = getParentKey();

	var jsonData = {	"id":orderKey,
						"supplierNo": supplierNo, 
						"updatedBy": auth.user_id, 
						"userNo": auth.id, 
						"lastUpdated":sqlNow()
					}
	var jsonStr = JSON.stringify(jsonData);
	alert("About to update an orders record with:  \n" + jsonStr) ;
	updateParent(orderKey, jsonStr);

}


/*
	updateParent() - calls the REST API to update the parent orders record.
 */
function updateParent(orderKey, jsonData) {

	var url = location.protocol + "//"  + window.location.hostname + "/orders/rest/orders.php" ;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			$("#restResponse").html(this.responseText);
			if (this.status == 200) {
				reportTransaction(orderKey);
			}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
		}
	}
	xhttp.open("PATCH", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonData);
	
}