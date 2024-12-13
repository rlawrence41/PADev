/* 

	This procedure will post the submitted return total to the customer's account.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postReturnTotal
  (paramOrderKey INTEGER)			/* Id for the customer return */
  COMMENT "Posts the return total to the customer's account."

  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN
  
	/* Delete any previously logged order total. */
	 
	 DELETE FROM ledger WHERE transType = "Return Total" AND orderKey = paramOrderKey;

	/* Insert a new order total transaction to the customer's account. */

	INSERT INTO ledger (
		transDate,
		transEvent,
		transType,
		accountNo,
		acctType,
		amount,
		orderKey,
/*	Skip the following...
		docNo,
		itemNo,
		receiptNo,
		specNo,
		lExported,
		comment,	*/
		updatedBy,
		userNo,
		lastUpdated)
	(SELECT
		o.orderDate,
		"RETURN",
		"Return Total",
		o.customerNo,
		"A",
		-o.total,
		o.id,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o WHERE o.id = paramOrderKey);
  END;

//
DELIMITER ;
