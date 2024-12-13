
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Test Accordion2</title>
  </head>
  <body>
    <h1>Test Accordion2</h1>

		<form class="bd-example">
		  <fieldset>
			<legend>Edit Contact</legend>



<?php

	$eol = "<br/>\n";

	// HTML Templates are in the templates folder.
	$includePath = "../templates";
	set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);
 	echo "Include Path:  " . get_include_path() . $eol;
	echo "FILE_USE_INCLUDE_PATH:  ";
	echo FILE_USE_INCLUDE_PATH;
	echo $eol;
 
$accordionGroup1 = <<<HTML
			<div class='form-group'>
				<input type='hidden' class='form-control' id='id'>
			</div>
			<div class='form-group'>
				<label for='contact_no'>Contact Number</label>
				<input type='text' class='form-control' id='contact_no' placeholder='Contact Number'>
			</div>
			<div class='form-group'>
				<label for='contact_id'>Contact Id</label>
				<input type='text' class='form-control' id='contact_id' placeholder='Short name for the account'>
			</div>
			<div class='form-group'>
				<label for='company'>Company</label>
				<input type='text' class='form-control' id='company' placeholder='Company or organization name'>
			</div>
			<div class='form-group'>
				<label for='nameprefix'>Prefix</label>
				<input type='text' class='form-control' id='nameprefix' placeholder='e.g.: Mr., Mrs., Ms., Dr., etc.'>
			</div>
			<div class='form-group'>
				<label for='first_name'>First Name</label>
				<input type='text' class='form-control' id='first_name' placeholder='First Name'>
			</div>
			<div class='form-group'>
				<label for='mid_name'>Middle Name</label>
				<input type='text' class='form-control' id='mid_name' placeholder='middle name or initial'>
			</div>
			<div class='form-group'>
				<label for='last_name'>Last Name</label>
				<input type='text' class='form-control' id='last_name' placeholder='Last Name'>
			</div>
			<div class='form-group'>
				<label for='namesuffix'>Suffix</label>
				<input type='text' class='form-control' id='namesuffix' placeholder='e.g.: Sr., Jr., III, Esq., Phd., etc.'>
			</div>
HTML;
#echo $accordionGroup1;
$html = file_get_contents("accordion.html", FILE_USE_INCLUDE_PATH);
$html = str_replace("{accordionName}", "Name", $html);
$html = str_replace("{accordionContent}", $accordionGroup1, $html);
echo $html;

$accordionGroup2 = <<<HTML
			<div class='form-group'>
				<label for='po_addr'>Street</label>
				<textarea class='form-control' id='po_addr' placeholder='Postal Service Street Address '></textarea>
			</div>
			<div class='form-group'>
				<label for='cour_addr'>Courier Address</label>
				<textarea class='form-control' id='cour_addr' placeholder='Complete street address for courier services'></textarea>
			</div>
			<div class='form-group'>
				<label for='city'>City</label>
				<input type='text' class='form-control' id='city' placeholder='City or town'>
			</div>
			<div class='form-group'>
				<label for='state_abbr'>State</label>
				<input type='text' class='form-control' id='state_abbr' placeholder='State Abbreviation'>
			</div>
			<div class='form-group'>
				<label for='country'>Country</label>
				<input type='text' class='form-control' id='country' placeholder='USA'>
			</div>
			<div class='form-group'>
				<label for='zip_code'>Zip Code</label>
				<input type='text' class='form-control' id='zip_code' placeholder='Postal Code'>
			</div>
			<div class='form-group'>
				<label for='countyabbr'>County</label>
				<input type='text' class='form-control' id='countyabbr' placeholder='County Abbreviation'>
			</div>
			<div class='form-group'>
				<label for='biltocntct'>Billing Contact</label>
				<input type='text' class='form-control' id='biltocntct' placeholder='Select a Billing Contact'>
			</div>
			<div class='form-group'>
				<label for='phone'>Phone</label>
				<input type='text' class='form-control' id='phone' placeholder='Enter the primary phone number.'>
			</div>
			<div class='form-group'>
				<label for='phone2'>Phone2</label>
				<input type='text' class='form-control' id='phone2' placeholder='Enter an alternate phone, FAX or cell number.'>
			</div>
			<div class='form-group'>
				<label for='email'>Email</label>
				<input type='text' class='form-control' id='email' placeholder='e.g. username@domain.com'>
			</div>
			<div class='form-group'>
				<label for='web_url'>Web Site</label>
				<input type='text' class='form-control' id='web_url' placeholder='Enter the URL of this contact\'s web site.'>
			</div>
			<div class='form-group'>
				<label for='webservice'>Web Service</label>
				<input type='text' class='form-control' id='webservice' placeholder='Enter the URL of a web service offered by this contact.'>
			</div>
HTML;
#echo $accordionGroup2;
$html = file_get_contents("accordion.html", FILE_USE_INCLUDE_PATH);
$html = str_replace("{accordionName}", "Address", $html);
$html = str_replace("{accordionContent}", $accordionGroup2, $html);
$html = str_replace("collapseOne", "collapseTwo", $html);
echo $html;


$accordionGroup3 = <<<HTML
			<div class='form-group'>
				<label for='fed_id_no'>Federal Id</label>
				<input type='password' class='form-control' id='fed_id_no' placeholder='Maintain only for tax reporting purposes.'>
			</div>
			<div class='form-group'>
				<label for='san'>SAN</label>
				<input type='text' class='form-control' id='san' placeholder='Standard Address Number'>
			</div>
			<div class='form-group'>
				<label for='pubnetid'>PubNet Id</label>
				<input type='text' class='form-control' id='pubnetid' placeholder='PubNet Id'>
			</div>
			<div class='form-group'>
				<label for='buyer_id'>Buyer Id</label>
				<input type='text' class='form-control' id='buyer_id' placeholder='for EDI purposes'>
			</div>
			<div class='form-group'>
				<label for='seller_id'>Seller Id</label>
				<input type='text' class='form-control' id='seller_id' placeholder='Your EDI Id for this contact'>
			</div>
			<div class='form-group'>
				<label for='password'>Password</label>
				<input type='text' class='form-control' id='password' placeholder='Enter a password for this contact.'>
			</div>
HTML;
#echo $accordionGroup3;
$html = file_get_contents("accordion.html", FILE_USE_INCLUDE_PATH);
$html = str_replace("{accordionName}", "EDI", $html);
$html = str_replace("{accordionContent}", $accordionGroup3, $html);
$html = str_replace("collapseOne", "collapseThree", $html);
echo $html;


$accordionGroup4 = <<<HTML
			<div class='form-group'>
				<label for='biography'>Biography</label>
				<input type='text' class='form-control' id='biography' placeholder='Generally used for promotional purposes'>
			</div>
			<div class='form-group'>
				<label for='portrait'>Portrait</label>
				<input type='text' class='form-control' id='portrait' placeholder='Upload an image.'>
			</div>
			<div class='form-group'>
				<label for='comment'>Comment</label>
				<input type='text' class='form-control' id='comment' placeholder='Comments added here will be private.'>
			</div>
HTML;
#echo $accordionGroup4;
$html = file_get_contents("accordion.html", FILE_USE_INCLUDE_PATH);
$html = str_replace("{accordionName}", "Promotion", $html);
$html = str_replace("{accordionContent}", $accordionGroup4, $html);
$html = str_replace("collapseOne", "collapseFour", $html);
echo $html;


$accordionGroup5 = <<<HTML
			<div class='form-check'>
				<input class='form-check-input' type='checkbox' id='author'>
				<label class='form-check-label' for='author'>Author?</label>
			</div>
			<div class='form-check'>
				<input class='form-check-input' type='checkbox' id='customer'>
				<label class='form-check-label' for='customer'>Customer?</label>
			</div>
			<div class='form-check'>
				<input class='form-check-input' type='checkbox' id='exclude'>
				<label class='form-check-label' for='exclude'>Exclude?</label>
			</div>
			<div class='form-check'>
				<input class='form-check-input' type='checkbox' id='mail_list'>
				<label class='form-check-label' for='mail_list'>Mailing List?</label>
			</div>
			<div class='form-check'>
				<input class='form-check-input' type='checkbox' id='sales_rep'>
				<label class='form-check-label' for='sales_rep'>Sales Representative?</label>
			</div>
			<div class='form-check'>
				<input class='form-check-input' type='checkbox' id='supplier'>
				<label class='form-check-label' for='supplier'>Supplier?</label>
			</div>
			<div class='form-check'>
				<input class='form-check-input' type='checkbox' id='warehouse'>
				<label class='form-check-label' for='warehouse'>Warehouse Location?</label>
			</div>
			<div class='form-check'>
				<input class='form-check-input' type='checkbox' id='employee'>
				<label class='form-check-label' for='employee'>Employee?</label>
			</div>
			<div class='form-check'>
				<input class='form-check-input' type='checkbox' id='approved'>
				<label class='form-check-label' for='approved'>Approved?</label>
			</div>
HTML;
#echo $accordionGroup5;
$html = file_get_contents("accordion.html", FILE_USE_INCLUDE_PATH);
$html = str_replace("{accordionName}", "Categories", $html);
$html = str_replace("{accordionContent}", $accordionGroup5, $html);
$html = str_replace("collapseOne", "collapseFive", $html);
echo $html;

$accordionGroup6 = <<<HTML
			<div class='form-group'>
				<label for='entered_by'>Entered By</label>
				<input type='text' class='form-control' id='entered_by' placeholder='Entered By'>
			</div>
			<div class='form-group'>
				<label for='lupdate'>Last Updated</label>
				<input type='date' class='form-control' id='lupdate' placeholder='Date of last update'>
			</div>
HTML;
#echo $accordionGroup6;
$html = file_get_contents("accordion.html", FILE_USE_INCLUDE_PATH);
$html = str_replace("{accordionName}", "Updates", $html);
$html = str_replace("{accordionContent}", $accordionGroup6, $html);
$html = str_replace("collapseOne", "collapseSix", $html);
echo $html;
	
?>


		  </fieldset>
		</form>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>