/* 

	This procedure will post the submitted receipt amount to the customer's account.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postReceipt
  (paramReceiptNo INTEGER)			/* Id for the receipt */
  COMMENT "Posts the receipt amount to the customer's account in the ledger."

  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN
  
	/* Delete any previously logged order total. */
	 
	 DELETE FROM ledger WHERE transType = "Amount Received" AND receiptNo = paramReceiptNo;

	/* Insert a new receipt transaction to the customer's account. */

	INSERT INTO ledger (
		transDate,
		transEvent,
		transType,
		accountNo,
		acctType,
		amount,
		receiptNo,
/*	Skip the following...
		docNo,
		orderKey,
		itemNo,
		specNo,
		lExported,
		comment,	*/
		updatedBy,
		userNo,
		lastUpdated)
	(SELECT
		r.recptDate,
		"RECEIPT",
		"Amount Received",
		r.customerNo,
		"A",
		-r.amount,
		r.id,
		r.updatedBy,
		r.userNo,
		now() AS lastUpdated
	  FROM receipt r WHERE r.id = paramReceiptNo);
  END;

//
DELIMITER ;
