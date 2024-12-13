<?php
include ("includes.php");

/*
 *	payment.php
 *
 *	This procedure is a REST compliant service for the payment resource.  
 *	It will facilitate interaction with the PubAssist mySQL data source.
 *
 */

// Anything that is unique to the payment resource should be declared in
// the paymentRequest sub-class.
class paymentRequest extends request{
	
	function __construct(){
		
		parent::__construct();
		
		//	Set the resource and keyField for this sub-class.
		$this->resource = "payment";
		$this->keyField = "id";
		
		//	Load the SQL templates for each request method to be supported.
		//	Unsupported methods should be left blank.
		$this->addMethodSQL('COUNT', 'payment_COUNT.sql');
		$this->addMethodSQL('GET', 'payment_GET.sql');
		$this->addMethodSQL('PUT', 'general_PUT.sql');
		$this->addMethodSQL('POST', 'payment_POST.sql');
		$this->addMethodSQL('PATCH', 'general_PATCH.sql');
		$this->addMethodSQL('DELETE', 'payment_DELETE.sql');	

	}
}


$REST = new paymentRequest();

// Return JSON for a GET request.  
if ($REST->method == "GET"){$REST->respondWithJSON();}

// Otherwise, respond with some HTML about the update(s).
else {$REST->respondWithResult();}

?>
