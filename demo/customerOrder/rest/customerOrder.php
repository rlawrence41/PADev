<?php
include ("includes.php");

/*
 *	customerOrder.php
 *
 *	This procedure is a REST compliant service for the customerOrder resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the customerOrder resource should be declared in
// the customerOrderRequest sub-class.
class customerOrderRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "customerOrder";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'customerOrder_COUNT.sql');
		$this->addMethodSQL('GET', 'customerOrder_GET.sql');
		$this->addMethodSQL('PUT', 'customerOrder_PUT.sql');
		$this->addMethodSQL('POST', 'customerOrder_POST.sql');
		$this->addMethodSQL('PATCH', 'general_PATCH.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', 'customerOrder_DELETE.sql');	

	}
}


$REST = new customerOrderRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
