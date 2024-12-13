/* 
	These procedures are unique to the appliedOrder transaction application.
	
 */


// Parse the content of the table to determine the total funds applied.
function calculateTotal() {
    var total = 0;
    var table = document.querySelector("#tableAdmin .table tbody");
    var rows = table.getElementsByTagName("tr");
    
    for (var i = 0; i < rows.length; i++) {
        var checkbox = rows[i].querySelector('input[type="checkbox"]');
		var orderPaidCell = rows[i].querySelector('[name="orderPaid"]')
        if (checkbox.checked) {
			if (checkbox.value != ""){
				var orderPaid = parseFloat(orderPaidCell.textContent);
				total += orderPaid;
			}
        }		
    }
    
//	alert("Total paid is: " + total.toFixed(2));

	return total;
}


/* 
 *  deleteOrderReceipt(keyValue) – deletes an order receipt.
 */

function deleteOrderReceipt(keyValue){
	
	var url = location.protocol + "//"  + window.location.hostname + 
	"/orderReceipt/rest/orderReceipt.php/orderReceipt/" + keyValue;
	
	// Give the user a chance to bail out.
	if (!confirm("Remove application of funds to " + keyValue)) {return;}
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			refreshTable(this.responseText) ;
		} 
	}
	xhttp.open("DELETE", url, true);  
	xhttp.send();
}


function exitApplyToOrders(receiptNo){
	
	// Capture the amount of this receipt.
	refreshTotals();
	var amtReceived = Number(document.getElementById("receivedDisplay").textContent);
	var totalApplied = Number(document.getElementById("totalDisplay").textContent);
	var amtRemaining = Number(document.getElementById("remainingDisplay").textContent);

	if (amtRemaining != 0) {
		// Ask the user whether to update the receipt amount.
		if (confirm("Would you like to change the receipt amount to what has been applied?")) {
			var amount = amtReceived - amtRemaining;
			updateReceiptAmount(receiptNo, amount);
		}
	}
	reportTransaction(receiptNo);
}


/*
	Capture the amount of the receipt from the parent record.
 */

function getReceiptAmount(){

	// Capture the amount of this receipt.
	var jsonStr = document.getElementById("selectedRec").textContent;
	var receiptObj = JSON.parse(jsonStr);
	var receiptNo = receiptObj.id;
	var amtReceived = Number(receiptObj.amount);

	return amtReceived;

}


/*
	postItemReceipts() - Upon exiting the appliedOrder step, item receipts must be 
	updated accordingly to keep them in sync'.  
	
 */
 
function postItemReceipts(receiptNo){

	// Assemble the parameters needed to call the stored procedure.
	// Sample:  {"id":"13", "contactId":"", "company":"COTTAGE INDUSTRY BOOKS", "namePrefix":"", "firstName":"U. OTTO", "midName":"", "lastName":"READMORE", "nameSuffix":"", "poAddr":"123 MAIN STREET", "courAddr":"U. OTTO READMORE\nTHIS IS AN ENTIRELY DIFFERENT ADDRESS FOR NON USPS COURIER SERVICES.", "city":"ANYTOWN", "stateAbbr":"NY", "country":"", "zipCode":"12345", "munAbbr":"", "billTo":"", "phone":"(866) 555-1212", "phone2":"", "email":"UOTTO@DEMOTITLES.COM", "webUrl":"", "webservice":"", "fedIdNo":"", "san":"", "pubnetId":"", "buyerId":"", "sellerId":"", "lExclude":0, "biography":"", "portrait":"", "comment":"", "password":"", "lAuthor":1, "lCustomer":1, "lMailList":0, "lSalesRep":0, "lSupplier":0, "lWarehouse":0, "lEmployee":0, "lApproved":0, "updatedBy":"RLAWRENCE", "userNo":"1", "lastUpdated":"2024-05-21 13:42:57"}
	var jsonData = {"receiptNo":receiptNo,
					"updatedBy":addQuotes(auth.user_id), 
					"userNo":auth.id, 
					"lastUpdated":addQuotes(sqlNow())
					}
											
	var jsonStr = JSON.stringify(jsonData);
	alert("About to update item receipts with:  \n" + jsonStr) ;

	var url = location.protocol + "//"  + window.location.hostname + 
			"/appliedItem/rest/appliedItem.php" ;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			$("#restResponse").html(this.responseText);
			if (this.status == 200) {
				exitApplyToOrders(receiptNo);
			}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
		}
	}
	xhttp.open("POST", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonStr);
	
}




// RefreshTotals - Update the summaryBand with critical amounts about this receipt.

function refreshTotals() {

//	alert("Refreshing summary band.");
	var jsonStr = document.getElementById("selectedRec").textContent;
	var receiptObj = JSON.parse(jsonStr);
	var receiptNo = receiptObj.id;
	var amtReceived = Number(receiptObj.amount);
	var totalApplied = calculateTotal();
	var amtRemaining = amtReceived - totalApplied;
	document.getElementById("receivedDisplay").textContent = amtReceived.toFixed(2);
	document.getElementById("totalDisplay").textContent = totalApplied.toFixed(2);
	document.getElementById("remainingDisplay").textContent = amtRemaining.toFixed(2);
}


/*
	rowToJson() builds a JSON record from the data in the appliedOrder table row.
 */

function rowToJson(element) {
	
	// Capture the parent receipt record.
	var jsonStr = document.getElementById("selectedRec").textContent;
	var receiptObj = JSON.parse(jsonStr);
	var receiptNo = receiptObj.id;
	var amtReceived = Number(receiptObj.amount);

	// Capture values from the candidate order record.
	var row = element.parentNode.parentNode;
	var i = row.rowIndex - 1;
	var orderKey = document.getElementById("orderKey" + i.toString()).textContent;
	var orderTotal = document.getElementById("orderTotal" + i.toString()).textContent;
	var surcharges = document.getElementById("surcharges" + i.toString()).textContent;

	var jsonData = {"orderKey": orderKey,
					"receiptNo": receiptObj.id,
					"customerNo": receiptObj.customerNo,
					"ordertotal": orderTotal,
					"orderPaid": orderTotal,
					"surcharges": surcharges,
					"srchgPaid": surcharges,		
					"updatedBy":auth.user_id, 
					"userNo":auth.id, 
					"lastUpdated":sqlNow()
					}
											
	var jsonStr = JSON.stringify(jsonData);
	return jsonStr;
}


/*
	saveOrderReceipt() adds an order receipt record.
 */ 

function saveOrderReceipt(element){
		
	var url = location.protocol + "//"  + window.location.hostname + 
				"/orderReceipt/rest/orderReceipt.php" ;

	// Build a JSON string from the data in the selected row of the table.
	var jsonData = rowToJson(element);
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			if (this.status == 200) {
				refreshTable(this.responseText);
				refreshTotals();
				}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
		}
	}
	xhttp.open("PUT", url, true);
	xhttp.send(jsonData);

}


/*	
	Take action based on whether the apply check box is checked.
 */

function showCheck(element){

	var message = "Checkbox " + element.id + " is " + element.checked + "\n" ;
	message += "Value is type " + typeof(element.value) + "\n";
	message += " and set to: " + element.value;
//	alert(message);
	
	var row = element.parentNode.parentNode;
	var i = row.rowIndex - 1;
	var orderPaidCell = document.getElementById("orderPaid" + i.toString());
	var orderTotalCell = document.getElementById("orderTotal" + i.toString());
	var orderBalanceCell = document.getElementById("orderBalance" + i.toString());

	// If checked, apply funds for the order total.
	if (element.checked) {

		saveOrderReceipt(element);

	}
	else { 
		// Otherwise, delete the order receipt.  
		// The key value for the order receipt will be assigned to the checkbox.
		// If there is no value, then there is no existing order receipt.
		if (element.value != "") deleteOrderReceipt(element.value);

	}

	
	// Refresh the summary band...
	refreshTotals();
	
}


// Add refreshTotals to the load event for the body.

//document.getElementById("receiptDetailId").addEventListener("load", refreshTotals();
refreshTotals();