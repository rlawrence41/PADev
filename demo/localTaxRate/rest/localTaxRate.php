<?php
include ("includes.php");

/*
 *	localTaxRate.php
 *
 *	This procedure is a REST compliant service for the localTaxRate resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the localTaxRate resource should be declared in
// the localTaxRateRequest sub-class.
class localTaxRateRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "localTaxRate";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'localTaxRate_COUNT.sql');
		$this->addMethodSQL('GET', 'localTaxRate_GET.sql');
		$this->addMethodSQL('PUT', 'general_PUT.sql');
		$this->addMethodSQL('POST', 'general_POST.sql');
		$this->addMethodSQL('PATCH', 'general_POST.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', 'localTaxRate_DELETE.sql');	

	}
}


$REST = new localTaxRateRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
