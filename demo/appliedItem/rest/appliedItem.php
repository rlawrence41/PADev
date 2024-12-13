<?php
include ("includes.php");

/*
 *	appliedItem.php
 *
 *	This procedure is a REST compliant service for the appliedItem resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the appliedItem resource should be declared in
// the appliedItemRequest sub-class.
class appliedItemRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "appliedItem";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'appliedItem_COUNT.sql');
		$this->addMethodSQL('GET', 'appliedItem_GET.sql');
		$this->addMethodSQL('PUT', 'general_PUT.sql');
		$this->addMethodSQL('POST', 'appliedItem_POST.sql');
		$this->addMethodSQL('PATCH', 'general_POST.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', 'appliedItem_DELETE.sql');	

	}

}


$REST = new appliedItemRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
