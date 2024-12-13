<?php

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

$url = "https://dev.pubassist.com/orders/rest/orders.php";
$orderKey = 15;
$shipToNo = 12;

$jsonData = json_encode([
	"id" => $orderKey,
	"shipToNo" => $shipToNo, 
	"updatedBy" => "rlawrence41", 
	"userNo" => 1, 
	"lastUpdated" => date('Y-m-d H:i:s')
]);


$eol = "<br/>\n";
echo "<h1>Testing postJsonToURL()</h1>" . $eol;
echo $url . $eol;
$response = postJsonToURL($url, $jsonData);
echo $response;


?>