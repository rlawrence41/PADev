<?php


/*
 *  
 *  In this example, the code constructs the URL for the API request by specifying the 
 *  search format as JSON and encoding the address parameter. It then uses 
 *  file_get_contents() to send the HTTP request and retrieve the JSON response. The 
 *  response is decoded using json_decode() into an associative array. Finally, the code 
 *  checks if the geocoding was successful by verifying if the response is not empty and 
 *  extracts the latitude and longitude from the response.
 *  
 */


//$address = "1600 Amphitheatre Parkway, Mountain View, CA 94043"; // Address to geocode
$eol = "<br>\n";
$address= "41 Lawrence Hts., Jericho, VT 05465";
echo "Geocode for address:  " . $address . $eol;

// Create the URL for the API request
$url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address);

// Set the user agent header
$options = [
  'http' => [
    'header' => 'User-Agent: MyGeocodingApp',
  ],
];

// Send the request to the API and decode the JSON response
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$result = json_decode($response, true);

// Check if the geocoding was successful
if (!empty($result)) {
  $latitude = $result[0]['lat'];
  $longitude = $result[0]['lon'];
  echo "Latitude: $latitude, Longitude: $longitude";
} else {
  echo "Geocoding failed.";
}


?>