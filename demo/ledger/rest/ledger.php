<?php
include ("includes.php");

/*
 *	ledger.php
 *
 *	This procedure is a REST compliant service for the ledger resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the ledger resource should be declared in
// the ledgerRequest sub-class.
class ledgerRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "ledger";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'ledger_COUNT.sql');
		$this->addMethodSQL('GET', 'ledger_GET.sql');
		$this->addMethodSQL('PUT', 'general_PUT.sql');
		$this->addMethodSQL('POST', 'general_POST.sql');
		$this->addMethodSQL('PATCH', 'general_POST.sql');	// functionally the same as POST.
		$this->addMethodSQL('DELETE', 'ledger_DELETE.sql');	

	}
}


$REST = new ledgerRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
