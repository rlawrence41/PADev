<?php

/*
 *	common.php - These variables and routines are shared among many php 
 *				scripts.
 *
 */
 
$eol = "<br/>\n";
$debug = false; 	// Turns debugging off.
#$debug = 1;		// Logs messages to the error log.
#$debug = 2;		// Causes debugging messages to be echoed.


/*	
 *	addTraceFields() -- Adds the standard trace fields to an array to be posted to REST.
 */
function addTraceFields(&$postData=array()){

	// Add the trace fields...
	
	$postData["updatedBy"] = $_SESSION['auth']['user_id']; 
	$postData["userNo"] = $_SESSION['auth']['id'];
	$postData["lastUpdated"] = date('Y-m-d H:i:s');

	return $postData;
}


/*
 *	authorized() -- Determines whether the user is authorized to run the 
 *				requesting application.
 */
function authorized($authCode=0){

	// First, capture the requested page.
	$_SESSION['HTTP_REFERER'] = $_SERVER['REQUEST_URI'];

	// If the user is not logged in, then prompt them to do so.
	if (empty($_SESSION['auth'])) {
		redirect("/login.php");
		exit;
	}
	
	// Check their authorization code against the one submitted by the 
	// requested application.
	if ($_SESSION['auth']['authCode'] >= $authCode) {return true;}
	
	// If not authorized, redirect to an unauthorized page.
	redirect("/unauthorized.html", true);

}


/*
 *	configFile() - returns the configuration file for the current domain.
 */
function configFile() {
	$serverName = $_SERVER['SERVER_NAME'] ;
	$configPath = dirname($_SERVER['DOCUMENT_ROOT']) . "/config/" ;
	$configFile = $configPath . $serverName . ".php" ;
	return $configFile ;
}


/*
 *	debug_msg() -- sends a message to the error log and possibly to
 *				the browser, depending on the setting of $debug.
 */
 
function debug_msg($msg="", $override=false, $header=""){
global $debug, $eol;

	if ($debug > 0 OR $override) {
		if ($header <> "") {error_log(str_pad($header, 60, "_", STR_PAD_BOTH), 0);}
#		$msg .= debug_backtrace() . $eol ;
		if ($msg <> "" ) {error_log($msg, 0);}
		if ($debug >= 2) {echo $msg . $eol;}

#		error_log(str_pad("Debug Msg", 60, "_", STR_PAD_BOTH), 0);
	}	
}


// Returns a valid U.S.P.S. address for the submitted contact.
function getPOAddress($contactObj){
	
	// Submitted contact should be a decoded JSON object...
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


/*
 *	Return a user-friendly name based on the authorization found in the 
 *	session variable.
 */
function friendlyName(){
	if (empty($_SESSION['auth'])) {return ""; }
	$nameStr = $_SESSION['auth']['contact_noSearch'];
	if (empty($nameStr)){return $_SESSION['auth']['user_id'];}
	
	$nameArray = explode(", ", $nameStr);
	return $nameArray[1];
}

/* There are many built in functions to process arrays, but there seems to be no 
	function to find the next element in the array given a key.  These functions, 
	getNext() and getPrevious() were found here:
	
	https://stackoverflow.com/a/4133495/10723550
 
	The comments say they are cyclic, but they are not.  Made slight changes to
	allow them to loop around the end of the array, forward and backward.
 
 */

function getNext(&$array, $curr_key)
{
    $next = 0;
    reset($array);

    do
    {
        $tmp_key = key($array);
        $res = next($array);
    } while ( ($tmp_key != $curr_key) && $res );

    if( !$res )
	{
		reset($array) ;
	}
    $next = key($array);

    return $next;
}


function getPrev(&$array, $curr_key)
{
    end($array);
    $prev = key($array);

    do
    {
        $tmp_key = key($array);
        $res = prev($array);
    } while ( ($tmp_key != $curr_key) && $res );

    if( !$res )
    {
		end($array);
    }
    $prev = key($array);

    return $prev;
}



/* includeResource -- This procedure attempts to do two things:

	1.  Add the folders for a resource application to the include path, and
	2.  Include the class definitions associated with the resource to the current
		script.

 */

function includeResource($resource) {

	// Check on the existence of the class definition for the resource.
	$resourceRoot = __DIR__ . "/{$resource}" ;
	$resourceRoot = str_replace("RAPIDS", "demo", $resourceRoot) ;  // So, this procedure can be used in the test.
    $classFile =  $resourceRoot . "/ui/classes/{$resource}.class.php";

#echo "Including resource: {$resource}, {$resourceRoot}...<br/>\n" ;

    // If the class file exists, require it.
	if (file_exists($classFile)) {
	
		// Include the class definition.
        require_once $classFile;

		// Include resource application folders into the include path.
		$includePath = get_include_path() ;
		if (strpos($includePath, $resourceRoot) === false) {
			$includePath .= PATH_SEPARATOR . "{$resourceRoot}";
			$includePath .= PATH_SEPARATOR . "{$resourceRoot}/ui/classes";
			$includePath .= PATH_SEPARATOR . "{$resourceRoot}/ui/css";
			$includePath .= PATH_SEPARATOR . "{$resourceRoot}/ui/js";
			$includePath .= PATH_SEPARATOR . "{$resourceRoot}/ui/templates";
			set_include_path($includePath);
		}
		else debug_msg("Skipping duplicate: {$resourceRoot}...") ;

		// Set the REST path for this application.
#		$GLOBALS['RESTroot'] = str_replace("common", "{$resource}", $GLOBALS['RESTroot']);

	}

}


/*
 *	missingWhere - Checks for the existence of a where clause in the submitted string.
 */
function missingWhere($sql){
	$pos1 = stripos($sql, "update");
	$pos2 = stripos($sql, "where");
#	$isUpdate =($pos1 !== false);

	// Note that if "update" is missing, stripos returns FALSE, which is == 0.
	$isUpdate =($pos1 === 0);
	$isWhere = ($pos2 === false);
	return ($isUpdate AND $isWhere);	
}


/*
 *	postToURL() -- uses CURL to forward an HTTP request from the server.
 */
function postToURL($url) {
global $sslCertPath;

#	echo "Posting to URL: " . $url;
	$ch = curl_init( $url );

	// Assign CAINFO to the specific SSL Cert ONLY IF it is self-authorized.
	if (!is_null($sslCertPath)){curl_setopt( $ch, CURLOPT_CAINFO, $sslCertPath);}
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
	// Disable SSL verification for development ONLY...
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 

	$response = curl_exec($ch);
//	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ( ! ($response)) {
		$errno = curl_errno($ch);
		$errstr = curl_error($ch);
		$response = "postToURL - CURL error: [{$errno}] {$errstr}.";
	}
	curl_close($ch);
	
	return $response;	
	
}


function postJsonToURL($url, $jsonData) {
    global $sslCertPath;

    // Initialize cURL session
    $ch = curl_init($url);

    // Assign CAINFO to the specific SSL Cert ONLY IF it is self-authorized.
    if (!is_null($sslCertPath)) {
        curl_setopt($ch, CURLOPT_CAINFO, $sslCertPath);
    }

    // Set cURL options for POST request
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Use the provided JSON data

    // Set cURL options for HTTP headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json'
    ));

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    // Disable SSL verification for development ONLY...
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // Execute cURL request and get the response
    $response = curl_exec($ch);
    
    // Check for errors
    if (!$response) {
        $errno = curl_errno($ch);
        $errstr = curl_error($ch);
        $response = "postToURL - CURL error: [$errno] $errstr.";
    }

    // Close cURL session
    curl_close($ch);

    return $response;
}


/*
 *	redirect - redirects the user to the submitted URL.
 */
function redirect($url, $permanent = false)
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);
    exit();
}

/*
 *	sendMail() - Send an email on behalf of this web site.
 */
function sendMail($to, $subject="", $message=""){

#	debug_msg($message, true, "sendMail1");
	$email = new webObj("email", "", $subject);

	// The message content was being escaped when the message was submitted
	// as an argument.  Add it as the description here.
	$email->description = $message;
#	debug_msg($email->description, true, "sendMail2");
	$email->template = "email.html";
	$body = $email->render();
#	debug_msg($body, true, "sendMail3");

	// Send the security code via email.
	$to = $_SESSION['auth']['email'];
	
	$header = array();
	$header[] = "From: paweb@pubassist.com";
	$header[] = "Reply-To: rlawrence@pubassist.com";  // Add a Reply-To header for additional clarity
	$header[] = "Return-Path: rlawrence@pubassist.com";
	$header[] = "MIME-Version: 1.0";
	$header[] = "Content-type: text/html; charset=utf-8";

	$headers = implode("\r\n", $header);
	// Set the sender address using the -f option
	$additional_headers = "-f paweb@pubassist.com";


	// Mail it...
	$success = mail($to, $subject, $body, $headers, $additional_headers);
	if (!$success) {
		$errorMessage = error_get_last()['message'];
	}

	return $success;
 }


function stripPhone($phoneStr){
	return preg_replace("#[[:punct:] ]#", "", $phoneStr);
}


// General purpose functions used in this procedure.

/*
 *	AddQuotes -- Adds quotation marks to the submitted string.
 */
function addQuotes($strValue){return '"' . $strValue . '"'; }

/*
	amountToWords -- converts the submitted amount into English words.
					Used primarily for check writing purposes.
					Complements of ChatGPT.

	// Test example
	$amount = 1217.56;
	$amountWords = amountToWords($amount);

 */
function amountToWords($amount) {
    $units = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
    $teens = ["Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
    $tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
    $scales = ["", "Thousand", "Million", "Billion"];

    if ($amount == 0) {
        return "Zero Dollars";
    }

    $dollars = floor($amount);
    $cents = round(($amount - $dollars) * 100);

    $words = "";
    $scaleIndex = 0;

    // Process dollars in chunks of 3 digits
    while ($dollars > 0) {
        $chunk = $dollars % 1000;
        if ($chunk > 0) {
            $chunkWords = convertChunk($chunk, $units, $teens, $tens);
            $chunkScale = $scales[$scaleIndex];
            $words = trim($chunkWords . " " . $chunkScale) . " " . $words;
        }
        $dollars = floor($dollars / 1000);
        $scaleIndex++;
    }

    $words = trim($words) . " Dollars";

    // Process cents
    if ($cents > 0) { $centWords = convertChunk($cents, $units, $teens, $tens); }
	else { $centWords = "No"; }
	$words .= " and " . $centWords . " Cents";
    return $words;
}


/* 
 *	Return a value based on the type of the field.
 */
function checkValue($fieldName, $value, $operator='='){

	debug_msg("Checking value: {$value} for field, {$fieldName}.", true, "Common Function CheckValue()"); 

	// Don't mess with a password.
	if ($fieldName == "password") { return addQuotes($value); }

	// Are we dealing with a number WITHOUT leading zeros (like a zip code)?
	if (isNumberNotString($fieldName, $value)) return $value; 
	
	// Empty values can be saved as NULL.
	if (empty($value)) { return 'NULL'; }	// No added quotes!

	// Do we have a NULL value?
	if ($value == "NULL"){ return 'NULL'; }	// No added quotes!
	
	// Is the LIKE operator in use?
	if (TRIM($operator) == "LIKE"){ 
		$value = $value . "%" ;
	}

	// Check for escapes and add quotes.
	$returnVal = safeStr($value); 
	return $returnVal ;

}

/*
 *	convertChunk -- Convert a three-digit chunk into words
 *				Used by amountToWords() above...
 */
function convertChunk($number, $units, $teens, $tens) {
	$chunkText = "";

	if ($number >= 100) {
		$chunkText .= $units[floor($number / 100)] . " Hundred ";
		$number %= 100;
	}

	if ($number >= 11 && $number <= 19) {
		$chunkText .= $teens[$number - 11] . " ";
	} elseif ($number >= 10) {
		$chunkText .= $tens[floor($number / 10)] . " ";
		$number %= 10;
	}

	if ($number > 0) {
		$chunkText .= $units[$number] . " ";
	}

	return trim($chunkText);
}


/*
 *	isNumberNotString - Zip codes with leading zeros are prime examples of numbers that
 *			should be interpreted as a string.  The is_numeric() function is not 
 *			sufficient.
 */
function isNumberNotString($fieldName, $value){

	if (is_numeric($value)){
		$valStr = strval($value);
		if (substr($valStr, 0, 1)=="0" AND strlen($valStr) >= 5) {
			debug_msg("Field {$fieldName} looks like a zip code.", false, "IsNumberNotString()"); 
			return false;
		}
		else {
			debug_msg("Field {$fieldName} looks like a number.", false, "IsNumberNotString()"); 
			return true ;
		}
	}
	return false ;	
}


/*
 *	Function safestr escapes the submitted string to make it safe for 
 *	potential SQL execution.
 */
function safeStr($unsafestr){
	
//	if (!is_string($unsafestr)) {return "";}

	$result = TRIM($unsafestr);				// Trim the value.
	$result = addslashes($result);			// Add Escapes.
	$result = addQuotes($result);			// Add quotes.
	
	debug_msg("Safe String: " . $result, true);
	return $result ;

}

