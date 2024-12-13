// The following procedures are unique to the orderItem application.

//  Called on a change to the titleNo element.
function getTitle(titleNo) {
	
	// 	Sample REST url:
	//	https://dev.pubassist.com/orderItem/rest/orderItem.php/orderItem/3
	
	var url = location.protocol + "//" + location.hostname ;
	url += "/title/rest/title.php/title/" + titleNo;
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {

		if (this.readyState == 4) { 

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
		
			if (this.status == 200) {
				jsonObj = JSON.parse(this.responseText);
				updateTitleFields(jsonObj[1][0]);
			}
		} 
	}
	xhttp.open("GET", url, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send();
	
}


// Update the deduction whenever the discount is updated.
function updateDeduction(){

	var price 	 = document.getElementsByName("column[price]")[0].value;
	var discount = document.getElementsByName("column[discount]")[0].value;
	var deduction = (price * (discount/100)).toFixed(2);
	var elements = document.getElementsByName("column[deduction]");
	elements[0].value = deduction;
}


// Update the discount whenever the deduction is updated.
function updateDiscount(){

	var price 	  = document.getElementsByName("column[price]")[0].value;
	var deduction = document.getElementsByName("column[deduction]")[0].value;
	var discount = (deduction/price*100).toFixed(2);
	var elements = document.getElementsByName("column[discount]");
	elements[0].value = discount;		
}

// This may not be used...but since I went to the trouble...
function updateExtended(){

	var quantity  = document.getElementsByName("column[quantity]")[0].value;
	var price 	  = document.getElementsByName("column[price]")[0].value;
	var deduction = document.getElementsByName("column[deduction]")[0].value;
	var extPrice  = quantity*(price-deduction);
	var extWeight = (quantity*shipweight).toFixed(2);
	var elements  =	document.getElementsByName("column[extPrice]");
	elements[0].value = extPrice;
	
}


// Update the form fields with values from the submitted title record.

function updateTitleFields(titleObj){

	if (typeof titleObj !== 'object') {
		return;
	}

	var elements = document.getElementsByName("column[titleId]");
	elements[0].value = titleObj.titleId;
	
	elements = document.getElementsByName("column[title]");
	elements[0].value = titleObj.title;
	
	elements = document.getElementsByName("column[price]");
	elements[0].value = titleObj.retPrice;
	
	elements = document.getElementsByName("column[shipWeight]");
	elements[0].value = titleObj.weight;

	if (titleObj.lInventory==true) {
		elements = document.getElementsByName("column[lInventory]");
		elements[0].checked = true;
	}
	
	if (titleObj.lConsignment==true) {
		elements = document.getElementsByName("column[lConsignment]");
		elements[0].checked = true;
	}

	if (titleObj.lTaxable==true) {
		elements = document.getElementsByName("column[lTaxable]");
		elements[0].checked = true;
	}

	// If the title is a subscription, set the subscription indicator and 
	//	the expiration date.
	if (titleObj.lSubscript==true) {
		elements = document.getElementsByName("column[lSubscript]");
		elements[0].value = titleObj.lSubscript;

		// set the expiration date for a year from the current date.
		elements = document.getElementsByName("column[expireDate]");
		elements[0].value = sqlYearFromNow();
	}

	// Default the quantity, discount and deduction fields to avoid NULLS.
	elements = document.getElementsByName("column[quantity]");
	elements[0].value = 1;

	elements = document.getElementsByName("column[discount]");
	elements[0].value = "0.00";

	elements = document.getElementsByName("column[deduction]");
	elements[0].value = "0.00";


	// Update the discount--based on a potential change in price.
//	updateDiscount();
}

