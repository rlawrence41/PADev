<?php




?>




<!doctype html>
<html lang="en">
  <head>
    <!-- FavIcon for this site -->
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Test Bootstrap Pagination</title>
  </head>
  <body>
    <h1>Test Bootstrap Pagination </h1>
	<h2>Generated from the Server </h2>

<!-- Pagination start -->

<h2>Centered</h2>
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item disabled" id="previous">
      <a class="page-link" id="previousPage" href="#" tabindex="-1" aria-disabled="true">Previous</a>
    </li>
    <!--li class="page-item" id="previous"><a id="previousPage" class="page-link" href="#">Previous</a></li-->
    <li class="page-item active" aria-current="page">
      <span class="page-link" id="firstPage">
        1
        <!--span class="sr-only">(current)</span-->
      </span>
    </li>
    <!--li class="page-item"><a id="firstPage" class="page-link" href="#">1</a></li-->
	<li class="page-item disabled">
		<a class="page-link" href="#"tabindex="-1" aria-disabled="true">...</a>
	</li>
    <li class="page-item"><a id="pageControl1" class="page-link" href="#">2</a></li>
    <li class="page-item"><a id="pageControl2" class="page-link" href="#">3</a></li>
    <li class="page-item"><a id="pageControl3" class="page-link" href="#">4</a></li>
	<li class="page-item disabled">
		<a class="page-link" href="#"tabindex="-1" aria-disabled="true">...</a>
	</li>
    <li class="page-item"><a id="lastPage" class="page-link" href="#">Last</a></li>	
    <li class="page-item" id="next"><a id="nextPage" class="page-link" href="#">Next</a></li>
  </ul>
</nav>


<!-- Pagination end -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Optional JavaScript -->

<script type="text/javascript">
	
/*
 *	The following variables retain the context for the page.
 */
	var resource 	= "{resource}";
	var pageNo		= 1;
	var pageControl = [2, 3, 4];
	var lastPage 	= 10;
	var sortBy 		= "{keyFieldName}" ;
	var filterStr 	= "" ;
	var restURI 	= "{localRESTURI}" ;
	var restResponse = "";

$(document).ready(function(){

	$("#lastPage").text(lastPage);		// Sets the page number for the last page control.

	$(".page-link").click(function(){

		var pageWanted = $(this).text();
		
		switch(pageWanted) {
		  case "Previous":
			pageNo-- ; 
			break;
		  case "Next":
			pageNo++ ; 
			break;
		  default:
			pageNo = pageWanted;
		}

		alert("Requested page: " + pageNo);
		paintPageControls();
		
	});


	function checkCurrent(id){
	
	// Set attributes of the submitted control based on whether it is the current page.
		var controlText = $(id).text();
		if (controlText == pageNo.toString()){
			$(id).attr("class:page-item active", "aria-current:page")
		}
		else {
			$(id).attr("class:page-item");
			$(id).removeAttr("aria-current");
		}
	}


	function checkNext(){
	
		if (pageNo >= lastPage) {
			//	Disables the "Next" page control.
			$("#next").attr("class", "page-item disabled");
			$("#nextPage").attr("tabindex:-1", "aria-disabled:true");			
			pageNo = lastPage;
		}
		else {
			//	Enables the "Next" page control.
			$("#next").attr("class", "page-item");
			$("#nextPage").attr("tabindex:0", "aria-disabled:false");			
		}
	}


	function checkPrevious(){

		if (pageNo <= 1) {
			//	Disables the "Previous" page control.
			$("#previous").attr("class", "page-item disabled");
			$("#previousPage").attr("tabindex:-1", "aria-disabled:true");			
			pageNo = 1;
		}
		else {
			//	Enables the "Previous" page control.
			$("#previous").attr("class", "page-item");
			$("#previousPage").attr("tabindex:0", "aria-disabled:false");			
		}
	}


	function paintPageControls() {
	
	// Renumber the page controls based on the current page.

		checkPrevious();
		checkNext();
		
		// Beginning and ending page numbers are already included.  So, start
		// numbering controls with no less than page 2, and no more than the next 
		// to last page.
		var range = pageControl.length;				// Number of page controls to paint.
		var maxStart = lastPage - range;			// No higher than the next to last page.
		var minStart = Math.max(pageNo, 2);			// Start at no less than 2.
		var start = Math.min(minStart, maxStart);	// Start at the lower of the two.
		var pageControlId = "";
		var i ;
		for (i=0; i<range; i++){
			pageControl[i] = start + i ;		// Saves the new page numbers to the array.
			controlNo = i + 1;
			pageControlId = "#pageControl" + controlNo.toString();
			$(pageControlId).text(pageControl[i]);
			checkCurrent(pageControlId);
		}
	}	
});

</script>	
	

  </body>
</html>