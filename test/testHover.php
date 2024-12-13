<?php

$list = <<<HTML
<ul>
<li><a href=# id="66675"> Larson Publications, Cash, Paul, 4936 State Route 414, Burdett, NY, 14818, larson@lightlink.com</a></li>
<li><a href=# id="65393"> Larson Publications, Fritchman, June, 4936 State Route 414, Burdette, NY, 14818, ifnisworld@yahoo.com</a></li>
<li><a href=# id="1609"> Active Books, Larsen, Don, 358 Lincoln Avenue, Livermore, CA, 94550, USA, dwl8@comcast.net</a></li>
<li><a href=# id="1688"> Thriller Press, Larsson, Tyler, 3440 Lake Tahoe Boulevard||, South Lake Tahoe, CA, 96152, tyler@thrillerpress.com</a></li>
</ul>
HTML;

/*
$list2 = <<<HTML
<ul>
<li>This is list item 1.</li>
<li>This is list item 2.</li>
<li>This is list item 3.</li>
<li>This is list item 4.</li>
<li>This is list item 5.</li>
</ul>
HTML;
*/
?>


<html>
<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<style>

	/* The following dictates the appearance of the search list */
	div.searchList {
		position: absolute; /* to position directly below the input element that activates the list. */
		background-color: white;
		padding: 10px;
		box-shadow: 10px 10px 30px gray;
		border: 1px solid gray;
		border-radius: 5px;
	}

	div.searchList ul {
	  list-style-type: none ;
	  padding-inline-start: 0px;
	}

	div.searchList li:hover {
	  background-color: lightgray;
	}
	
	div.searchList a:link {
	  text-decoration: none;
	}

</style>

</head>
<body>

<h1>This is just the HTML presentation of a Search List.</h1>


<div id="searchList1" class="searchList">
<?php echo $list ?>
</div>

<script>

$(document).ready(function(){	

	$("#searchList1 a").click(function(){
		alert($(this).attr("id"));
	});

});

</script>


</body>
</html>