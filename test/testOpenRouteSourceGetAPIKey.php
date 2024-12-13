<?php

$url = "https://openrouteservice.org/dev-dashboard/";
$response = file_get_contents($url);

// Extract the API key from the response HTML
preg_match('/"YOUR_API_KEY"/', $response, $matches);

// Print the API key
echo "API Key: " . $matches[0];


?>