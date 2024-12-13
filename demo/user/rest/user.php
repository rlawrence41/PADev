<?php
include ("includes.php");

/*
 *	user.php
 *
 *	This procedure is a REST compliant service for the user resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the user resource should be declared in
// the userRequest sub-class.
class userRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "user";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'user_COUNT.sql');
		$this->addMethodSQL('GET', 'user_GET.sql');
		$this->addMethodSQL('PUT', 'general_PUT.sql');
		$this->addMethodSQL('POST', 'general_POST.sql');
		$this->addMethodSQL('PATCH', 'general_POST.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', 'user_DELETE.sql');	

	}



	// Extend buildSQL to either hash or verify the password.
	public function buildSQL($input=array()){

		// If the request is to store data, (i.e. the $input array has data)
		// then search the posted data for the password field.
		if (!empty($input)) {
			$password = $input["password"];
			debug_msg("Password: " . $password, false, "authRequest->buildSQL()");
			
			// Set the password ONLY if one was provided...
			if (strtolower($password) == "undefined") {
				// Lose the password element...
				unset($input["password"]);
			}
			else {
				// Save the hash--not the raw password.
				$input["password"] = password_hash($password, PASSWORD_DEFAULT);
			}
		}
		
		parent::buildSQL($input);	
	}

}



$REST = new userRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
