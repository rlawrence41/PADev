
// The order status SELECT element has an option for either "Shipped" or "Received"
// depending on the type of order being presented.  Since customer returns and 
// purchase orders both used "Received", this procedure focuses on changing the option
// ONLY for customer orders (i.e. orderType = "C").

function changeStatusOption(orderTypeElement) {
	
	let orderType = orderTypeElement.value;
	var elements = document.getElementsByName('column[status]');
	let select = elements[0];

	if (orderType == "C") {
		select[1].value = "Shipped";
		select[1].text = "Shipped";
	}
	else {
		select[1].value = "Received";
		select[1].text = "Received";
	}
//	alert("Select Option 1 is now: " + select[1].value); 
}
