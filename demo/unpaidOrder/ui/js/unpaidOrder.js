/* 
	These procedures are unique to the unpaidOrder transaction application.

 */


/*
	applyToOrder() adds an order receipt record.
 */ 

function applyToOrder(){
		
	var url = location.protocol + "//"  + window.location.hostname + 
				"/unpaidOrder/saveOrderReceipt .php" ;

	// Add the parent key value from the customer order transaction.
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
 *  deleteUnpaidOrder(keyValue) – calls the REST API to delete an instance.
 */

function deleteUnpaidOrder(keyValue){
	
	var url = location.protocol + "//"  + window.location.hostname + 
	"/orderReceipt/rest/orderReceipt.php/orderReceipt/" + keyValue;
	
	// Give the user a chance to bail out.
	if (!confirm("Remove application of funds to " + keyValue)) {return;}
	
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




function showCheck(element){

	var isChecked = element.checked;
	var message = "Checkbox " + element.id + " is " + isChecked + "\n" ;
	message += "Value is type " + typeof(element.value) + "\n";
	message += " and set to: " + element.value;
//	alert(message);
	
	// Sum up the amount applied to orders.
	calculateTotal();
	
	// If the checkmark is on, then insert a new order receipt.
	
/*	if (isChecked) applyToOrder(orderKey, receiptNo)
	else deleteOrderReceipt(element.value) ;	*/
	
}


/*	Update the total applied amount from the orderPaid column */
function tallyPaid(){
	
	var totalPaid = 0;
	var nodelist = document.getElementsByName("orderPaid");
	nodelist.forEach(
		function(node, index) {
			totalPaid += Number(node.innerText);
		}
	);
	alert("Total paid is: " + totalPaid.toFixed(2));
//	document.getElementById("totalPaid").innerHTML = totalPaid;
	
}


// Parse the content of the table to determine the total funds to apply.
function calculateTotal() {
    var total = 0;
    var table = document.querySelector("#tableAdmin .table tbody");
    var rows = table.getElementsByTagName("tr");
    
    for (var i = 0; i < rows.length; i++) {
        var checkbox = rows[i].querySelector('input[type="checkbox"]');
        var orderPaidCell = rows[i].querySelector('[name="orderPaid"]');
        if (checkbox.checked) {
			var totalCell = rows[i].querySelector('[name="total"]')
			orderPaidCell.textContent = totalCell.textContent;
            var orderPaid = parseFloat(totalCell.textContent);
            total += orderPaid;
        }
		else { orderPaidCell.textContent = "0.00" }
			
    }
    
    // Display the total (you can modify this part to suit your needs)
	alert("Total paid is: " + total.toFixed(2));

//    document.getElementById("totalDisplay").textContent = "Total: " + total.toFixed(2);
}