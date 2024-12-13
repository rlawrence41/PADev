<?php
include ("includes.php");
session_start();

/*
 *	auth.php
 *
 *	This procedure is a REST compliant service for the authorization resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

class authRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "auth";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'auth_COUNT.sql');
		$this->addMethodSQL('GET', 'auth_GET.sql');
		$this->addMethodSQL('PUT', 'auth_PUT.sql');
		$this->addMethodSQL('POST', 'auth_POST.sql');
		$this->addMethodSQL('PATCH', 'auth_POST.sql');	// functionally the same as POST.
#		$this->addMethodSQL('DELETE', 'auth_DELETE.sql');	

	}


	// Extend buildSQL to either hash or verify the password.
	public function buildSQL($input=array()){

		// If the request is to store data, (i.e. the $input array has data)
		// then search the posted data for the password field.
		if (!empty($input)) {
			$password = $input["password"];
#			debug_msg("Password: " . $password, false, "authRequest->buildSQL()");
			$input["password"] = password_hash($password, PASSWORD_DEFAULT);
		}
		
		return parent::buildSQL($input);	
	}


	// This method replaces the default method in the request class.
	public function respondWithJSON(){
	
		debug_msg("Entering subclass method...", false, "authRequest->respondWithJSON()");
		// For the authentication resource, the default response should actually 
		// be an authentication.  The password (neither the original text nor the 
		// hash) should ever be returned.  
		
		// Preserve the password, and remove it from the query.
		if (isset($this->column['password'])){
			$password = $this->column['password'] ;
			
			// Remove the password from the column property to remove it from 
			// the where clause in the query.
#			$this->column['password'] = "";
			unset($this->column['password']);
		
			// Use strict comparison.
			$this->compare = "strict";
		
			// Parse the HTTP request.
			if ($this->parseRequest()){

				// Execute the resulting SQL command.
				$result = $this->executeSQL();

				// NOTE that $result will NOT be an array if the result is an empty set.
#				debug_msg("Result Array?: " . (is_array($result) ? "true" : "false"));

				// Results should only be available for a GET request.
				if ($this->method == "GET" && is_array($result)){

					// How big is the result?
					$resultLen = $result[0]["count"]; // Taken from the summary.
#					debug_msg("Result Length: " . $resultLen, true, "authRequest->respondWithJSON()");
#					debug_msg("Result Length Type: " . gettype($resultLen), true);
					
					// No records returned means that the user is unauthorized.
					if ($resultLen == 0){
						debug_msg("User was not found.");
						return $this->unauthorized();
					}

					// Forbid any more than a single account.
					if (intval($resultLen) > 1) {
						$message = "Query is not specific enough: {$resultLen}";
						$this->error = $message;
						header('X-PHP-Response-Code: 403', true, 403);
						echo $message;
						return false;		
					}

					// If nothing was returned, exit unauthorized...
					if (empty($result[1])) {return $this->unauthorized();}

					// Test the result by looking for the password.
					try {
						$hash = $result[1][0]["password"];
					}
					catch (Exception $e) {
						$message = "Password was not found: " . $e->getMessage();
						debug_msg($message);
						return $this->unauthorized();
					}

					debug_msg("Authenticating...", true, "auth->respondWithJson()");
#					debug_msg("Password: {$password}", true);
					debug_msg("Hash: {$hash}", true);

					// Authenticate the account.
					if (password_verify($password, $hash)) {

						// Lose the password element...
						unset($result[1][0]["password"]);

						// Password is valid!
						// Encode the results to JSON to respond to the client.
						$json = json_encode($result);
						
						// Save the authorization to the session.
						// JUST the authorization--NOT the count.
						$_SESSION['auth']=$result[1][0];

						// Respond with the results.
						header('Content-Type: application/json');
						echo $json;
						return true;
					}
					else {
						$message = "Password is invalid";
						debug_msg($message, true);
						return $this->unauthorized();
					}
				}
			}
		}
		return $this->unauthorized();
	}

	/*
	 * 	The standard respondWithResult() function returns the SQL commands that were
	 *	executed.  That shouldn't happen when updating user's password.
	 */
	public function respondWithResult(){

		// Parse the HTTP request.
		if ($this->parseRequest()){

			// Execute the resulting SQL command.
			$result = $this->executeSQL();

			if ($result){
				
				// Save the authorization to the session.
				// JUST the authorization--NOT the count.
				$_SESSION['auth']=$result[1][0];

				echo "User credentials were successfully updated.";
			}
		}
	}


	private function unauthorized(){
	
		$message = "Authentication failed.";
		$this->error = $message;
		header('X-PHP-Response-Code: 401', true, 401);
		echo $message;
		return false;								
	
	}
	
}

$REST = new authRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
