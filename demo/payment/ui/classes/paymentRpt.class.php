<?php

/*
 *	paymentRpt.class.php - This is the report for the payment transaction.
 *					This particular report generates checks as labels.
 *
 */


// Define a page component.
class paymentRptPage extends labelPage {
	
	public $labelTemplate = "LibertyChecks-L-MP123B.html";	// specific to this label.
	public $labelsPerPage = 3;
	
	public function __construct() {
		
		parent::__construct("payment", 
							"paymentLabelPage", 
							"Payments",
							"Checks suitable for printing.");
		
		$this->scripts[] = "ui/js/payment.js";

		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"payment", 
								"id");
		$this->context = $context;			// Rendered separately.
		
	}
	
	
	/* 
		The check writing label needs to convert the amount field into words.
		It makes more sense to do this with the result set rather than trying
		to put this into a query or stored procedure.
		
		// Test example
		$amount = 1217.56;
		$amountWords = amountToWords($amount);
		
	 */
	public function render($record=null){
		


		$html = parent::render($record);  // Note: $record is null in this call.
		
		// The result set should be in place now.
		// Process through the result set and replace each occurrance of the 
		// {amountInWords} token.
		
		$token = '{amountInWords}';

#$eol = "<br/>\n";
#echo "Looking for: "  . $token . $eol;
		foreach ($this->resultSet as $key => $record){
			$amount = $record["payAmount"];
			$amountInWords = amountToWords($amount);	// in common.php

			// str_replace replaces ALL occurrances--regardless of the setting of count.
#			$count = 1;
#			$html = str_replace($token, $amountInWords, $html, $count);

			// Do this instead...
			// https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
			$pos = strpos($html, $token);  // Find the first occurance of the token.

#echo "Position found: " . strval($pos) . $eol;

			if ($pos !== false) {
				$html = substr_replace($html, $amountInWords, $pos, strlen($token));
			}

		}
		return $html;
	}
}


