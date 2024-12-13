<?php
include ("includes.php");

/*
 *	<<resource>>.php
 *
 *	This procedure is a REST compliant service for the <<resource>> resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the <<resource>> resource should be declared in
// the <<resource>>Request sub-class.
class <<resource>>Request extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "<<resource>>";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', '<<resource>>_COUNT.sql');
		$this->addMethodSQL('GET', '<<resource>>_GET.sql');
		$this->addMethodSQL('PUT', 'general_PUT.sql');
		$this->addMethodSQL('POST', 'general_POST.sql');
		$this->addMethodSQL('PATCH', 'general_PATCH.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', '<<resource>>_DELETE.sql');	

	}
}


$REST = new <<resource>>Request();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
