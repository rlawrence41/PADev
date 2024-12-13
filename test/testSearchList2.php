<?php 

/* 
 *	testSearchListClass.php -- This procedure instantiates a searchList 
 *	and associates it with a text input element.
 */

$eol = "<br/>\n";
include ("includes.php");
//include ("searchList.class.php");
//include ("contactRequest.class.php");

$REST = new contactRequest();
$searchList = new searchList($REST, "payeeId");
$listHtml = $searchList->render();

//<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


$html = <<<THISPAGE

<!DOCTYPE html>
<html>
<head>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="/ui/js/searchList2.js"></script>
</head>
<body>

<h2>Test the searchList Class</h2>

<form action="#">
	<div class='form-group'>
		<input type='text' 
			class='form-control' 
			id='payeeId' 
			name="column[payeeId]" 
			value="0"
			disabled/>
		<label for='payeeIdSearch'>Payee</label>
		<input type='text' 
			class='form-control' 
			id='payeeIdSearch' 
			name="column[payeeIdSearch]" 
			value=""
			placeholder='Last name, first name, company'
			autocomplete="off"/>
	</div>
</form>

{$listHtml}

</body> 
</html>

THISPAGE;

// If simply refreshing the list, return only the list HTML.
if (isset($_GET['refresh'])){
	if ($_GET['refresh'] == "searchList") {echo $listHtml;}
}
else {echo $html;}

?>

