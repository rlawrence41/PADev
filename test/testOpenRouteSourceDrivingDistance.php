<?php

$origin = "8.681495,49.41461"; // Latitude and longitude of the origin
$destination = "8.687872,49.420318"; // Latitude and longitude of the destination
$api_key = "YOUR_API_KEY"; // Your OpenRouteService API key

// Create the URL for the API request
$url = "https://api.openrouteservice.org/v2/directions/driving-car?api_key=" . $api_key . "&start=" . $origin . "&end=" . $destination;

// Set the appropriate headers for the request
$headers = array(
  "Accept: application/json",
  "Content-Type: application/json"
);

// Initialize cURL and set the options
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

// Send the request to the API
$response = curl_exec($curl);

// Check if the request was successful
if ($response !== false) {
  // Decode the JSON response
  $result = json_decode($response, true);

  // Extract the driving distance from the response
  $distance = $result['features'][0]['properties']['segments'][0]['distance'];
  echo "Driving Distance: $distance meters";
} else {
  echo "Unable to retrieve driving distance.";
}

// Close cURL
curl_close($curl);

?>