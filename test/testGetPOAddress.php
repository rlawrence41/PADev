<?php


	// Returns a valid U.S.P.S. address for the submitted contact.
	function getPOAddress($contactObj){
		
		$contact = get_object_vars($contactObj);
		
	   // Initialize the address components
		$addressParts = [];

		// Start with the name.
		$nameParts = [];
				
		if (!empty($contact['namePrefix'])) $nameParts[] = $contact['namePrefix'];
		if (!empty($contact['firstName'])) $nameParts[] = $contact['firstName'];
		if (!empty($contact['midName'])) $nameParts[] = $contact['midName'];
		if (!empty($contact['lastName'])) $nameParts[] = $contact['lastName'];
		if (!empty($contact['nameSuffix'])) {
			$nameParts[] = ", " . $contact['nameSuffix'];
		}

		// Combine parts of the name into one line
		if (!empty($nameParts)) {
			$addressParts[] = implode(' ', $nameParts);
		}

		// Add the company name.
		if (!empty($contact['company'])) $addressParts[] = $contact['company'];

		// Add the street address
		if (!empty($contact['poAddr'])) $addressParts[] = $contact['poAddr'];

		// Add the city, state, and ZIP code
		$cityStateZip = [];
		if (!empty($contact['city'])) $cityStateZip[] = $contact['city'];
		if (!empty($contact['stateAbbr'])) $cityStateZip[] = $contact['stateAbbr'];
		if (!empty($contact['zipCode'])) $cityStateZip[] = $contact['zipCode'];

		// Combine city, state, and ZIP into one line
		if (!empty($cityStateZip)) $addressParts[] = implode(', ', $cityStateZip);
		
		// Add the country.
		if (!empty($contact['country'])) $addressParts[] = $contact['country'];
		
		// Join all parts with a newline character to form the complete address string
		return implode("\n", $addressParts);
		
	}
	

	// Get the submitted contact from REST. 
	function getContact($id) {

		// Capture the contact.
		// Sample REST call:
		//https://dev.pubassist.com/contact/rest/contact.php/contact/{$shipToNo}
		
		$url = "https://dev.pubassist.com/contact/rest/contact.php/contact/{$id}" ;
		$jsonStr = postToURL($url);
		$jsonObj = json_decode($jsonStr);
	
		// Skip the count.  
		// Even though a single record is returned, it is still the first cell of an array.
		$contactObj = $jsonObj[1][0];

		return $contactObj;
	}
	
// Here's the test...

include_once ("includes.php");

$eol = "<br/>\n";
echo "<h1>Testing getPOAddr()</h1>" . $eol;
	
$contactObj = getContact(13);

echo var_dump($contactObj);
echo $eol;

$poAddress = getPOAddress($contactObj);

echo "<pre>" . $eol;
echo $poAddress . $eol;
echo "</pre>" . $eol;

?>