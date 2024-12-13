/* 

	This procedure will post the inventory transactions for the submitted order
	to release the items from inventory.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE shipItems
  (paramOrderKey INTEGER)			/* Id for the customer order */
  COMMENT 'Posts inventory transactions for the submitted customer order.'

  MODIFIES SQL DATA                 /* Data access clause */

  `customerOrder`:
  BEGIN
  DECLARE oType CHAR(1);
  DECLARE oTransDate DATETIME;
   
	/* Make sure we are processing a customer order. */
	select orderType INTO oType from orders WHERE id = paramOrderKey;
	if (oType != "C") then
		LEAVE `customerOrder`;
	end if;

	/* Delete any previously logged inventory transactions for the ordered items. */
	 DELETE FROM inventory WHERE itemNo IN (SELECT id from orderItem WHERE orderKey = paramOrderKey);

	/* If the shipping date is empty (null) then use the current date and time. */
	SELECT if(shipDate is NULL, now(), shipDate) INTO otransDate 
		FROM orders WHERE id = paramOrderKey;

	/* Remove ordered items from the supplier's inventory. */
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
		o.supplierNo,
		-oi.quantity,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o JOIN orderItem oi ON oi.orderKey = o.id
	  WHERE o.id = paramOrderKey
		AND (oi.itemStatus NOT IN ("A","B","X","Y")
			OR oi.itemstatus IS NULL));


/*	Release the ordered items from inventory.	*/
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
		if(o.terms IN ("Consignment", "Transfer"),"I", "R"),
		if(o.terms IN ("Consignment", "Transfer"),o.shipToNo, 0),
		oi.quantity,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o JOIN orderItem oi ON oi.orderKey = o.id
	  WHERE o.id = paramOrderKey
		AND (oi.itemStatus NOT IN ("A","B","X","Y")
			OR oi.itemstatus IS NULL));

		
	/* Set item states */
	UPDATE orderItem SET itemStatus = "S" WHERE orderKey = paramOrderKey
		AND (itemStatus NOT IN ("A","B","X","Y"));
	
	/* Set the order status based on the shipment. */
	UPDATE orders SET status = IF(status="Open", "Shipped", "Closed"),
		shipDate = oTransDate
	WHERE id = paramOrderKey;

  END;


//
DELIMITER ;
