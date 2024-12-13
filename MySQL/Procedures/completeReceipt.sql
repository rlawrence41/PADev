DELIMITER //

/* This is the post-processing procedure for the receipt transaction. */

CREATE OR REPLACE PROCEDURE completeReceipt(
	IN parReceiptNo INTEGER,		-- the receipt to post liabilities for
	IN parUserNo INTEGER			-- the current user id for the trace fields
	)

  MODIFIES SQL DATA 
  BEGIN
	
	CALL postReceipt(parReceiptNo);				/* Post the receipt amount to the ledger */
	CALL postReceiptLiabilities(parReceiptNo);	/* Post title liabilities to the ledger. */

  END;

//
DELIMITER ;
