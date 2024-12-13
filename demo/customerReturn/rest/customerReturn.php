<?php
include ("includes.php");

/*
 *	customerReturn.php
 *
 *	This procedure is a REST compliant service for the customerReturn resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the customerReturn resource should be declared in
// the customerReturnRequest sub-class.
class customerReturnRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "customerReturn";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'customerReturn_COUNT.sql');
		$this->addMethodSQL('GET', 'customerReturn_GET.sql');
		$this->addMethodSQL('PUT', 'customerReturn_PUT.sql');
		$this->addMethodSQL('POST', 'customerReturn_POST.sql');
		$this->addMethodSQL('PATCH', 'general_PATCH.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', 'customerReturn_DELETE.sql');	

	}
}


$REST = new customerReturnRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
