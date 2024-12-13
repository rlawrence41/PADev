/* 
	These procedures are unique to the receipt transaction application.
	
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


/*
	addParent() generates a template parent record and adds it to the Receipt 
	resource--generating a new key.  The new transaction appears in the table.
 */ 

function addParent(){

	var url = location.protocol + "//"  + window.location.hostname + "/receiptDetail/rest/receiptDetail.php" ;
	var jsonStr = "";	// Might be used later.
	
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
	getReceipt() retrieves the parent receipt record via the REST API.
 */

function getReceipt(receiptNo){
	
	// 	Sample REST url:
	//	https://dev.pubassist.com/orders/rest/orders.php/orders/3
	
	var url = location.protocol + "//" + location.hostname ;
	url += "/receipt/rest/receipt.php/receipt/" + receiptNo;
	
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
	postProcessReceipt() kicks off the post-process for the transaction via the REST API.

	The post-process is implemented through a POST HTTP request and is implemented
	as a stored procedure in the database.
 */

function postProcessReceipt(receiptNo){
	
	// 	Sample REST url:
	//	https://dev.pubassist.com/orders/rest/orders.php/orders/3
	
	var url = location.protocol + "//" + location.hostname ;
	url += "/receiptDetail/rest/receiptDetail.php/receiptDetail/" + receiptNo;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4) { 
		
			if (this.status == 200) {
				alert("This receipt has been successfully processed.");
				location.assign("receiptDetailAdmin.php");
			}
			else {
				alert("There was a problem processing this receipt: \n" +
					this.responseText);
			}
		} 
	}

	xhttp.open("POST", url, true);
	xhttp.send();

}


/*
	updateCustomer() updates the receipt record with the bill to contact id.

 */
function updateCustomer(customerNo=0) {

	var receiptObj = getSelected();
	var receiptNo = receiptObj.id;

		
	// Sample:  {"id":"13", "contactId":"", "company":"COTTAGE INDUSTRY BOOKS", "namePrefix":"", "firstName":"U. OTTO", "midName":"", "lastName":"READMORE", "nameSuffix":"", "poAddr":"123 MAIN STREET", "courAddr":"U. OTTO READMORE\nTHIS IS AN ENTIRELY DIFFERENT ADDRESS FOR NON USPS COURIER SERVICES.", "city":"ANYTOWN", "stateAbbr":"NY", "country":"", "zipCode":"12345", "munAbbr":"", "billTo":"", "phone":"(866) 555-1212", "phone2":"", "email":"UOTTO@DEMOTITLES.COM", "webUrl":"", "webservice":"", "fedIdNo":"", "san":"", "pubnetId":"", "buyerId":"", "sellerId":"", "lExclude":0, "biography":"", "portrait":"", "comment":"", "password":"", "lAuthor":1, "lCustomer":1, "lMailList":0, "lSalesRep":0, "lSupplier":0, "lWarehouse":0, "lEmployee":0, "lApproved":0, "updatedBy":"RLAWRENCE", "userNo":"1", "lastUpdated":"2024-05-21 13:42:57"}
	var jsonData = {"id":receiptNo,
					"customerNo":customerNo,
					"updatedBy":auth.user_id, 
					"userNo":auth.id, 
					"lastUpdated":sqlNow()
					}
											
	var jsonStr = JSON.stringify(jsonData);
	var url = location.protocol + "//" + location.hostname ;
	url += "/receipt/rest/receipt.php";

	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			$("#restResponse").html(this.responseText);
			if (this.status == 200) {
				alert(this.responseText);
				reportTransaction(receiptNo);
			}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
		}
	}

	xhttp.open("PATCH", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonStr);

}


/* 
	updateReceiptAmount updates the parent receipt record with the submitted amount.
 */

function updateReceiptAmount(receiptNo, amount){

	var jsonData = {"id":receiptNo,
					"amount": amount.toFixed(2),
					"updatedBy":auth.user_id, 
					"userNo":auth.id, 
					"lastUpdated":sqlNow()
					}
	var jsonStr = JSON.stringify(jsonData);
	
	var url = location.protocol + "//"  + window.location.hostname + 
				"/receipt/rest/receipt.php" ;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			if (this.status == 200) {
				refreshTable(this.responseText);
				reportTransaction(receiptNo);
				}
			else {
				alert("There was a problem updating the receipt:  " + this.responseText);
				return;
			}
		}
	}
	xhttp.open("POST", url, true);
	xhttp.send(jsonStr);
	
	
}