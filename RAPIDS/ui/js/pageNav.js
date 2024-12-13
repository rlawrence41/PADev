/*
 *	PageNav.js -- These routines are for navigating to different pages within
 *			the current result set.
 *
 * 			This procedure depends on the context variable being declared and 
 *			scoped to this application.
 *
 *
	var pageNo = 1;
	var lastPage = 100;
	var perPage = 10;
	var filterStr = ""; 
 */
 
/*
 *	NewPage - User requests a new page by a click on the pagination controls.
 */
function newPage(pageControl){
	
	var pageWanted = pageControl.innerText.trim();
	var priorPage = pageNo;

	// Have to check the GoTo control separately.
	if (pageWanted.startsWith("Go")) {
		var inputId = pageControl.id + "Input";
		var inputElement = document.getElementById(inputId);
		pageWanted = inputElement.value;
	}

	// Which page was requested?
//	alert("Requesting page " + pageWanted + " from " + pageControl.id );

	switch(pageWanted) {
	case "<<" :
		if (pageNo > 1) {pageNo--;}
		break;
	case ">>" :
		if (pageNo < lastPage) {pageNo++;}
		break;
	default: ;
		// Make sure we are within the page range.
		var newPage = Number(pageWanted);
		if ( newPage >= 1 && newPage <= lastPage) {
			pageNo = newPage;
			pageNo = Number(pageWanted);
		}
		else {alert("Requested page is out of range.");}
	}
//	alert("New page is: " + pageNo);

	refreshCurrentPage(priorPage);

	// Refresh the table based on the new page selection.
	refreshTable();

}

/*	RefreshCurrentPage - refreshes the current page control with the new 
 *			page number.
 *
 */
function refreshCurrentPage(priorPage=1){
	
	// Preserve the "current page" control content.
	var currentPageControl = document.getElementById("pnc3");
	if (currentPageControl === null) {return;}
	var html = currentPageControl.innerHTML;
	
	// Note: I tried using innerText, but when it is replaced, the new-line
	// character in the text gets converted to an HTML line break element 
	// "<br/>".  That kills the function of the bootstrap page navigation 
	// control.  So, I replace the entire html content.
	
//	html = html.replace(priorPage.toString(), pageNo.toString());
	
	html = "<span class='page-link'>" + pageNo.toString() + 
			"\n<span class='sr-only'>(current)</span></span>"
	
	currentPageControl.innerHTML = html;
}


/*	RefreshLastPage - refreshes the last page control based on the number of 
 *			records in the filtered set.
 *
 */
function refreshLastPage(){

	// Capture the last page control;
	var lastPageControl = document.getElementById("pnc5");
	if (lastPageControl === null) {return;}

	// The record count should be found in a hidden input element.
	var countElement = document.getElementById("count");
	if (countElement == null){return;}
	var count = countElement.value;
	
	// Calculate the last page--based on the record count.
	var lPage = count/perPage ;
	lastPage = Math.ceil(lPage);
	
	var html = "<span class='page-link'>" + lastPage.toString() + "</span>";
	lastPageControl.innerHTML = html;

}


 
 