// The following procedures are unique to the inventory application.

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


// Update the form fields with values from the submitted title record.

function updateTitleFields(titleObj){

	if (typeof titleObj !== 'object') {
		return;
	}

	var elements = document.getElementsByName("column[titleId]");
	elements[0].value = titleObj.titleId;
	

}
