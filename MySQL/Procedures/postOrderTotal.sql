/* 

	This procedure will post the submitted order total to the customer's account.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postOrderTotal
  (paramOrderKey INTEGER)			/* Id for the customer order */
  COMMENT "Posts the order total to the customer's account."


  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN
  
	/* Delete any previously logged order total. */
	 
	 DELETE FROM ledger WHERE transType = "Order Total" AND orderKey = paramOrderKey;

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
		"ORDER",
		"Order Total",
		o.customerNo,
		"A",
		o.total,
		o.id,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o WHERE o.id = paramOrderKey);
  END;

//
DELIMITER ;
