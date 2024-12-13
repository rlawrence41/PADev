
/*
 *	refreshList() - An AJAX request to refresh the list with potentially new data.
 *				This procedure is specific to each search list object in the page.
 */
function refreshList(svElement, searchListURL) {
	
	var searchVal = svElement.value;
	// Don't bother if the search value is too short.
	if (searchVal.length < 3) {
		return;
	}

	// Add the "searchList" response and search value to the searchURL...
	// Be sure to encode the search value so that spaces and special characters 
	// may be included...
	var url = searchListURL + "?searchVal=" + encodeURIComponent(searchVal);
//	alert("URL:  " + url);

	// Instantiate the request object.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			
//			if ( this.status == 200 ){

			  // Fabricate the search list Id from the search value element id.
			  var svElementId = svElement.id;
			  var slId = svElementId.replace("Value", "List");
			  var slElement = document.getElementById(slId);

//			}
			
			// Save the response to the searchList division.
			slElement.innerHTML = this.responseText;	// Update the list.
			slElement.style.display="block";
		}
	}
	xhttp.open("GET", url, true);  //"True" indicates asynchronous!
	xhttp.send();

}


function repositionList(svElement){
	// Fabricate the search list Id from the search field id.
	var svElementId = svElement.id;
	var searchListId = svElementId.replace("Search", "List");
	var searchList = document.getElementById(searchListId);
}


/*
 *	selectItem() -- captures the list item content and Id from the list
 *					and assigns them to the searchValue and foreign key fields
 *					respectively.
 */
function selectItem(listItem){
	
	// Capture the search list DIV element, and it's Id.
	// slElement is the entire Search List HTML element.
	var slElement = listItem.parentElement.parentElement ;
	var slId = slElement.id;

	// Replace the word, "List" with "FKey" to obtain the Id for the foreign key element.
	// fkElement is the Foreign Key element, the input element where the selection is to be saved.
	var fkId = slId.replace("List", "FKey");
	var fkElement = document.getElementById(fkId);
	
	// Likewise, the search field should have the same id + "Search".
	// svElement is the input element where the selected list VALUE is to be displayed.
	var svId = slId.replace("List", "Value");
	var svElement = document.getElementById(svId);

	// The selected item's ID attribute is the value we want to save as the foreign key.
	var fkValue = listItem.id;
 	if (fkValue.length > 0){fkElement.value = listItem.id; }
	
	// Assign the selected list item content to the search field.
	svElement.value = listItem.innerText;
	
	// Hide the search list.
	slElement.style.display = "none";
	
	// Fire an onchange event.
	fkElement.dispatchEvent(new Event("change"));
	
}