<?php
include ("includes.php");

/*
 *	purchaseOrder.php
 *
 *	This procedure is a REST compliant service for the purchaseOrder resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the purchaseOrder resource should be declared in
// the purchaseOrderRequest sub-class.
class purchaseOrderRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "purchaseOrder";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'purchaseOrder_COUNT.sql');
		$this->addMethodSQL('GET', 'purchaseOrder_GET.sql');
		$this->addMethodSQL('PUT', 'purchaseOrder_PUT.sql');
		$this->addMethodSQL('POST', 'purchaseOrder_POST.sql');
		$this->addMethodSQL('PATCH', 'general_PATCH.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', 'purchaseOrder_DELETE.sql');	

	}
}


$REST = new purchaseOrderRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
