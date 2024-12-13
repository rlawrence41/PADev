<?php
/*
 * testAuthREST.php - This procedure was developed to test the results of a GET
 *					request to the authREST component.
 *
 */

include_once ("includes.php");

// Here's the restURI for the authorization resource.  
// Use $_SERVER['SERVER_NAME'] to avoid hard-coding the host name.
$restURI = "https://" . $_SERVER['SERVER_NAME'] .
			"/auth/authREST.php/auth";

$REST = new authRequest();
$REST->method = "GET" ;
$REST->column['contact_id'] = "NotARealUser" ;
$REST->column['password'] = "asgdhdghdkfkj" ;
$eol = "<br/>\n";

#$jspn = $REST->respondWithJSON();

//  The following copied from respondWithJson()...

		// For the authentication resource, the default response should actually 
		// be an authentication.  The password (neither the original text nor the 
		// hash) should ever be returned.  
		
		// Preserve the password, and remove it from the query.
		if (isset($REST->column['password'])){
			$password = $REST->column['password'] ;
			
			// Remove the password from the column property to remove it from 
			// the where clause in the query.
			$REST->column['password'] = "";
		
		
			// Parse the HTTP request.
			if ($REST->parseRequest()){

				// Execute the resulting SQL command.
				$result = $REST->executeSQL();
				echo "Result Array?: " . (is_array($result) ? "true" : "false") . $eol;

				// Results should only be available for a GET request.
				if ($REST->method == "GET" && is_array($result)){

					// How big is the result?
					$resultLen = count($result);
					echo "Result Length: " . strval($resultLen) . $eol;

					// Forbid any more than a single account.
					if ($resultLen > 1) {
						$message = "Query is disallowed.";
						$REST->error = $message;
						header('X-PHP-Response-Code: 403', true, 403);
						echo $message;
						return false;		
					}

					echo "Result[0] type: " . gettype($result[0]) . $eol;
					echo "Result[0] Empty?: " . (empty($result[0]) ? "true" : "false") . $eol;
					var_dump($result);
					echo $eol;

					if (empty($result[0])) {return $REST->unauthorized();}
					
					// Test the result by looking for the password.
					try {
						$hash = $result[0]["password"];
					}
					catch (Exception $e) {
						$message = $e->getMessage();
						echo $message . $eol ;
						return $REST->unauthorized();
					}

					// Authenticate the account.
					if (password_verify($password, $hash)) {

						// Lose the password element...
						unset($result[0]["password"]);

						// Password is valid!
						// Encode the results to JSON to respond to the client.
						$json = json_encode($result);

						// Respond with the results.
						header('Content-Type: application/json');
						echo $json;
						return true;
					}
				}
			}
		}
		return $REST->unauthorized();
?>


