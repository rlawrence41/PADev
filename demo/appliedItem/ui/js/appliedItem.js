/* 
	These procedures are unique to the appliedItem transaction application.
	
 */


// Process through the table to apply funds to selected items.

function applyToSelected() {
	
	// First, gather available funds from the receipt and returns.
	var available = gatherAvailableFunds();
	
	// What is the total of the selected items to be paid for?
	var total = sumItems();
	
	// Compare the available funds to the item total.
	var proportion = available/total;
	
	// Apply the available funds proportionally to the selected items.
	payItems(available, proportion);
	
	refreshTotals();
}



// Parse the content of the table to determine the total funds applied.

function calculateTotal() {
    var total = 0;
    var table = document.querySelector("#tableAdmin .table tbody");
    var rows = table.getElementsByTagName("tr");
    
    for (var i = 0; i < rows.length; i++) {
        var checkbox = rows[i].querySelector('input[type="checkbox"]');
		var amountCell = rows[i].querySelector('[name="amount"]')
        if (checkbox.checked) {
			if (checkbox.value != ""){
				var amount = parseFloat(amountCell.textContent);
				total += amount;
			}
        }		
    }
    
//	alert("Total paid is: " + total.toFixed(2));

	return total;
}


// Getting the totals to refresh has been a challenge.  This function is
// used to preserve the timing of these events outside of the AJAX call to
// update the itemReceipt.

function closeFormAndRefreshTotals(){
	
	returnToTable(this.responseText);	// Closes the modal form and refreshes the table.
	refreshTotals();					// Should calculate totals based on the updated table.
	
}


 
//  deleteItemReceipt(keyValue) – deletes an item receipt.

function deleteItemReceipt(keyValue){
	
	var url = location.protocol + "//"  + window.location.hostname + 
	"/itemReceipt/rest/itemReceipt.php/itemReceipt/" + keyValue;
	
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


/*	editItemReceipt - User request to edit an existing itemReceipt.
		Note that this is a clone of editInstance.  However, since this application
		edits and saves to a different resource, these unique procedures are 
		necessary.
 */
 
function editItemReceipt(responseText, formId="adminForm"){
	
	// Save the response to an element to register the change.
	$("#restResponse").html(responseText, formId);
	
	// Prepare the form to edit an item receipt.
	setFormToEditItemRcptMode(formId);
	
	// Clear the form.
	clearForm(formId);
	
	// Load the form with the selected instance.
	loadFormFromJSON(formId, responseText);
		
	// Show the modal form.
	var jqDivId = "#" + formId + "Div";
	$(jqDivId).modal('show');
}

/*
	exitApplyToItems - finishes the apply to items step.
	
 */

function exitApplyToItems(receiptNo){
	
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


/* gatherAvaiableFunds() - returns the amount of the receipt plus funds from 
	selected returns.
 */	
 
function gatherAvailableFunds() {
	
	// Get the amount of this receipt.
	var amtReceived = getReceiptAmount();

	// Step through the table to gather funds from returned items.
	var available = sumReturns();

	// Don't forget that return amounts are negative.  
	available -= amtReceived;
	
	return -available;

}


/*
 	getItemReceipt() - User request to edit an instance by a click on the edit 
		control.  getItemReceipt() calls the REST API to read an instance from the 
		database.

		This is a clone of getInstance().  However, this application is for
		the appliedItem view, but it saves to the itemReceipt table.  That 
		deviation requires a separate process that begins here.
 */
 
function getItemReceipt(keyValue){
	
	// In this case, it is possible that the keyValue is not available.
	if (keyValue==""){
		alert("Please select the item before attempting to edit.") ;
		return;
	}

	var url = location.protocol + "//"  + window.location.hostname + 
				"/itemReceipt/rest/itemReceipt.php/itemReceipt/" + keyValue;
	var jsonData;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4) { 

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
		
			if (this.status == 200) {
				// Here's the necessary change to this process!
				editItemReceipt(this.responseText);
				//**********************************************
			}
		} 
	}
	xhttp.open("GET", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send();
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


//	payItems() -- Automatically, and proportionally, applies the available funds 
//	to the selected items.  It calculations the proportion of available funds to
//	the total for the items, and prorates the funds to each item by that proportion.

function payItems(available, proportion){
	
    var total = 0;
    var table = document.querySelector("#tableAdmin .table tbody");
    var rows = table.getElementsByTagName("tr");
    
    for (var i = 0; i < rows.length; i++) {
        var checkbox = rows[i].querySelector('input[type="checkbox"]');
		var amountCell = rows[i].querySelector('[name="amount"]')
		var amount = parseFloat(amountCell.textContent);
		
        if (checkbox.checked) {
			if (amount > 0){
				
				// Adjust the amount applied by the proportion.
				amount *= proportion;
				
				// Store the new amount to the item in the table.
				amountCell.textContent = amount.toFixed(2);
			}
        }		
    }

	return total;
}


/*

	postOrderReceipts() - Upon exiting the appliedItem step, order receipts must be 
	updated accordingly to keep them in sync'.  

 */
 
function postOrderReceipts(receiptNo){

	// Assemble the parameters needed to call the stored procedure.
	// Sample:  {"id":"13", "contactId":"", "company":"COTTAGE INDUSTRY BOOKS", "namePrefix":"", "firstName":"U. OTTO", "midName":"", "lastName":"READMORE", "nameSuffix":"", "poAddr":"123 MAIN STREET", "courAddr":"U. OTTO READMORE\nTHIS IS AN ENTIRELY DIFFERENT ADDRESS FOR NON USPS COURIER SERVICES.", "city":"ANYTOWN", "stateAbbr":"NY", "country":"", "zipCode":"12345", "munAbbr":"", "billTo":"", "phone":"(866) 555-1212", "phone2":"", "email":"UOTTO@DEMOTITLES.COM", "webUrl":"", "webservice":"", "fedIdNo":"", "san":"", "pubnetId":"", "buyerId":"", "sellerId":"", "lExclude":0, "biography":"", "portrait":"", "comment":"", "password":"", "lAuthor":1, "lCustomer":1, "lMailList":0, "lSalesRep":0, "lSupplier":0, "lWarehouse":0, "lEmployee":0, "lApproved":0, "updatedBy":"RLAWRENCE", "userNo":"1", "lastUpdated":"2024-05-21 13:42:57"}
	var jsonData = {"receiptNo":receiptNo,
					"updatedBy":addQuotes(auth.user_id), 
					"userNo":auth.id, 
					"lastUpdated":addQuotes(sqlNow())
					}
											
	var jsonStr = JSON.stringify(jsonData);
	alert("About to update order receipts with:  \n" + jsonStr) ;

	var url = location.protocol + "//"  + window.location.hostname + 
				"/appliedOrder/rest/appliedOrder.php" ;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			$("#restResponse").html(this.responseText);
			if (this.status == 200) {
				exitApplyToItems(receiptNo);
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


// Update the summaryBand with critical amounts about this receipt.

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


//	rowToJson() builds a JSON record from the data in the appliedOrder table row.

function rowToJson(element) {
	
	// Capture the parent receipt record.
	var jsonStr = document.getElementById("selectedRec").textContent;
	var receiptObj = JSON.parse(jsonStr);
	var receiptNo = receiptObj.id;
	var amtReceived = Number(receiptObj.amount);

	// Capture values from the candidate item record.
	var row = element.parentNode.parentNode;
	var i = row.rowIndex - 1;
	var itemNo = document.getElementById("itemNo" + i.toString()).textContent;
	var titleNo = document.getElementById("titleNo" + i.toString()).textContent;
	var titleId = document.getElementById("titleId" + i.toString()).textContent;
	var orderKey = document.getElementById("orderKey" + i.toString()).textContent;
	var remainQty = document.getElementById("remainQty" + i.toString()).textContent;
	var effPrice = document.getElementById("effPrice" + i.toString()).textContent;
	var amount = remainQty * effPrice;

	var jsonData = {
					"itemNo": itemNo,
					"titleNo": titleNo,
					"titleId": titleId,
					"orderKey": orderKey,
					"customerNo": receiptObj.customerNo,
					"receiptNo": receiptObj.id,
					"remainQty": remainQty,
					"applyQty": remainQty,
					"amount": amount,
					"updatedBy":auth.user_id, 
					"userNo":auth.id, 
					"lastUpdated":sqlNow()
					}
											
	var jsonStr = JSON.stringify(jsonData);
	return jsonStr;
}


//	saveItemReceipt() -- inserts an item receipt record to the database.
// The difference between updateItemReceipt and saveItemReceipt is from where
// the itemReceipt data is gathered.  
// This procedure gathers the data from the table row.

function saveItemReceipt(element){
		
	var url = location.protocol + "//"  + window.location.hostname + 
				"/itemReceipt/rest/itemReceipt.php" ;

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


//	setFormToEditItemRcptMode - Sets the modal form up for editing an item receipt.
//		Note that this is a clone of setformToEditMode().  This unique procedure
//		is necessary because the form saves to the itemReceipt resource--rather
//		than this appliedItem resource associated with this application.

function setFormToEditItemRcptMode(formId="adminForm") {

	// Set the form's action to edit an instance of the resource.
	var jqFormId = "#" + formId;
	var jqFormDescription = jqFormId + "Description";
	var jqButtonId = jqFormId + "Continue" ; 
	$(jqFormDescription).html("Edit this instance.  Click below to save change.");
	$(jqButtonId).off("click");		// Remove previous click event handler.
	$(jqButtonId).html("Save Changes");
	$(jqButtonId).click(function (){
		updateItemReceipt(formId);
	});

	// Set the "Continue" button to execute on ENTER.
	setPrimaryButton(jqFormId, jqButtonId);
}


	
//	showCheck() -- Take action based on whether the apply check box is checked.

function showCheck(element){

	var message = "Checkbox " + element.id + " is " + element.checked + "\n" ;
	message += "Value is type " + typeof(element.value) + "\n";
	message += " and set to: " + element.value;
//	alert(message);
	
	var row = element.parentNode.parentNode;
	var i = row.rowIndex - 1;
	var remainQtyCell = document.getElementById("remainQty" + i.toString());
	var amountCell = document.getElementById("amount" + i.toString());

	// If checked, apply funds for the order total.
	if (element.checked) {

		saveItemReceipt(element);

	}
	else { 
		// Otherwise, delete the item receipt.  
		// The key value for the item receipt will be assigned to the checkbox.
		// If there is no value, then there is no existing item receipt.
		if (element.value != "") deleteItemReceipt(element.value);

	}
	
	// Refresh the summary band...
	refreshTotals();
	
}

// Sum the amounts to be applied for selected items.

function sumItems() {
    var total = 0;
    var table = document.querySelector("#tableAdmin .table tbody");
    var rows = table.getElementsByTagName("tr");
    
    for (var i = 0; i < rows.length; i++) {
        var checkbox = rows[i].querySelector('input[type="checkbox"]');
		var amountCell = rows[i].querySelector('[name="amount"]')
		var amount = parseFloat(amountCell.textContent);
		
        if (checkbox.checked) {
			if (amount > 0){
				total += amount;
			}
        }		
    }

	return total;
}


// Sum the amounts available from selected returns.

function sumReturns() {
    var total = 0;
    var table = document.querySelector("#tableAdmin .table tbody");
    var rows = table.getElementsByTagName("tr");
    
    for (var i = 0; i < rows.length; i++) {
        var checkbox = rows[i].querySelector('input[type="checkbox"]');
		var amountCell = rows[i].querySelector('[name="amount"]')
		var amount = parseFloat(amountCell.textContent);
		
        if (checkbox.checked) {
			if (amount < 0){
				total += amount;
			}
        }		
    }

	return total;
}


// Update the amount whenever the applyQty is updated.
function updateAmount(){

	var effPrice 	= document.getElementsByName("column[effPrice]")[0].value;
	var applyQty 	= document.getElementsByName("column[applyQty]")[0].value;
	var amount 		= (applyQty * effPrice).toFixed(2);
	var elements 	= document.getElementsByName("column[amount]");
	elements[0].value = amount;
}


// The difference between updateItemReceipt and saveItemReceipt is from where
// the itemReceipt data is gathered.  
// This procedure gathers the data from the adminForm.

function updateItemReceipt(formId="adminForm") {

	var url = location.protocol + "//"  + window.location.hostname + 
				"/itemReceipt/rest/itemReceipt.php" ;

	// Build a JSON string from the data in the admin form.
	var jsonData = jsonFromForm(formId);
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			if (this.status == 200) {
				closeFormAndRefreshTotals();
				}
			else {
				alert("There was a problem updating this instance:  " + this.responseText);
				return;
			}
			refreshTotals();
		}
	}
	xhttp.open("PATCH", url, true);
	xhttp.send(jsonData);

}


// Add refreshTotals to the load event for the body.

//document.getElementById("receiptDetailId").addEventListener("load", refreshTotals();
refreshTotals();

