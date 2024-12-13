/* 

	This procedure will post the submitted purchase order total to the supplier's 
	account.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postPOTotal
  (paramOrderKey INTEGER)			/* Id for the purchase order */
  COMMENT 'Posts the purchase order total to the ledger.'

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
		updatedBy,
		userNo,
		lastUpdated)
	(SELECT
		o.orderDate,
		"P.O.",
		"Order Total",
		o.supplierNo,
		"L",
		o.total,
		o.id,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o WHERE o.id = paramOrderKey);
  END;

//
DELIMITER ;
