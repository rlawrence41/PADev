<?php
include ("includes.php");

/*
 *	unpaidOrder.php
 *
 *	This procedure is a REST compliant service for the unpaidOrder resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the unpaidOrder resource should be declared in
// the unpaidOrderRequest sub-class.
class unpaidOrderRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "unpaidOrder";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'unpaidOrder_COUNT.sql');
		$this->addMethodSQL('GET', 'unpaidOrder_GET.sql');
		$this->addMethodSQL('PUT', 'general_PUT.sql');
		$this->addMethodSQL('POST', 'general_POST.sql');
		$this->addMethodSQL('PATCH', 'general_POST.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', 'unpaidOrder_DELETE.sql');	

	}
}


$REST = new unpaidOrderRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
