/* 

	This procedure will post the inventory transactions for the submitted customer 
	return.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE returnItems
  (paramOrderKey INTEGER)			/* Id for the customer order */
  COMMENT 'Posts inventory transactions for the submitted customer return.'

  MODIFIES SQL DATA                 /* Data access clause */
  
  `return`:
  BEGIN
  DECLARE oType CHAR(1);
  DECLARE oTransDate DATETIME;
  
	/* Make sure we are processing a customer return. */
	select orderType INTO oType from orders WHERE id = paramOrderKey;
	if (oType != "R") then
		LEAVE `return`;
	end if;

	/* Delete any previously logged inventory transactions for the returned items. */
	 DELETE FROM inventory WHERE itemNo IN (SELECT id from orderItem WHERE orderKey = paramOrderKey);

	/* If the shipping date is empty (null) then use the current date and time. */
	SELECT if(shipDate is NULL, now(), shipDate) INTO otransDate 
		FROM orders WHERE id = paramOrderKey;

	/* Return items from the shipTo's inventory. */
	INSERT INTO inventory (
		transDate,
		itemNo,
		titleNo,
		titleId,
		itemCondtn,
		invState,
		location,
		quantity,
		updatedBy,
		userNo,
		lastUpdated)
	(SELECT oTransDate as transDate,
		oi.id AS itemNo,
		oi.titleNo,
		oi.titleId,
		oi.itemCondtn,
		"I",
		o.shipToNo,
		oi.quantity,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o JOIN orderItem oi ON oi.orderKey = o.id
	  WHERE o.id = paramOrderKey
		AND (oi.itemStatus NOT IN ("A","B","X","Y")
			OR oi.itemstatus IS NULL));


/*	Depending on the terms, remove the items from either the ship to warehouse,
	or from the released inventory state.	*/
	INSERT INTO inventory (
		transDate,
		itemNo,
		titleNo,
		titleId,
		itemCondtn,
		invState,
		location,
		quantity,
		updatedBy,
		userNo,
		lastUpdated)
	(SELECT o.shipDate as transDate,
		oi.id AS itemNo,
		oi.titleNo,
		oi.titleId,
		oi.itemCondtn,
		if(o.terms IN ("Consignment", "Transfer"),"I", "R"),  /* Available or released? */
		if(o.terms IN ("Consignment", "Transfer"),o.shipToNo, 0), 
		-oi.quantity,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o JOIN orderItem oi ON oi.orderKey = o.id
	  WHERE o.id = paramOrderKey
		AND (oi.itemStatus NOT IN ("A","B","X","Y")
			OR oi.itemstatus IS NULL));

	/* Set item states */
	UPDATE orderItem SET itemStatus = "R" WHERE orderKey = paramOrderKey
		AND (itemStatus NOT IN ("A","B","X","Y"));
	
	/* Set the order status and shipment date after the return. */
	UPDATE orders SET status = IF(status="Paid", "Closed", "Received"),
		shipDate = oTransDate
	WHERE id = paramOrderKey;

  END;


//
DELIMITER ;
