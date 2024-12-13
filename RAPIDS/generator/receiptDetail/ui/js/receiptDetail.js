/* 
	These procedures are unique to the receiptDetail transaction application.
	
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
	addParent() generates a template parent record and adds it to the receiptDetail 
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
	getreceipt() retrieves the parent receipt record via the REST API.
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
