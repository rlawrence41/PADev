<?php
include ("includes.php");

/*
 *	appliedOrder.php
 *
 *	This procedure is a REST compliant service for the appliedOrder resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the appliedOrder resource should be declared in
// the appliedOrderRequest sub-class.
class appliedOrderRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "appliedOrder";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'appliedOrder_COUNT.sql');
		$this->addMethodSQL('GET', 'appliedOrder_GET.sql');
		$this->addMethodSQL('PUT', 'general_PUT.sql');
		$this->addMethodSQL('POST', 'appliedOrder_POST.sql');
		$this->addMethodSQL('PATCH', 'general_POST.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', 'appliedOrder_DELETE.sql');	

	}

}


$REST = new appliedOrderRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
