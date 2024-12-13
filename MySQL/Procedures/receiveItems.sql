/* 

	This procedure will post the inventory transactions for the submitted purchase order.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE receiveItems
  (paramOrderKey INTEGER)			/* Id for the purchase order */
  COMMENT 'Posts inventory transactions for the submitted purchase order.'

  MODIFIES SQL DATA                 /* Data access clause */
  
  `receive`:
  BEGIN
  DECLARE oType CHAR(1);
  DECLARE oTransDate DATETIME;
  
	/* Make sure we are processing a purchase order. */
	select orderType INTO oType from orders WHERE id = paramOrderKey;
	if (oType != "P") then
		LEAVE `receive`;
	end if;

	/* Delete any previously logged inventory transactions for the received items. */	 
	 DELETE FROM inventory WHERE itemNo IN (SELECT id from orderItem WHERE orderKey = paramOrderKey);

	/* If the shipping date is empty (null) then use the current date and time. */
	SELECT if(shipDate is NULL, now(), shipDate) INTO otransDate 
		FROM orders WHERE id = paramOrderKey;

	/* Receive items from the supplier. */
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
		if(o.status IN ("Open", "Paid"), "P", "I"),	/* Purchased or Available? */
		o.shipToNo,
		oi.quantity,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o JOIN orderItem oi ON oi.orderKey = o.id
	  WHERE o.id = paramOrderKey
		AND (oi.itemStatus NOT IN ("A","B","X","Y")
			OR oi.itemstatus IS NULL));


	/* Set item states */
	UPDATE orderItem SET itemStatus = "P" WHERE orderKey = paramOrderKey
		AND (itemStatus NOT IN ("A","B","X","Y"));
	
	/* Set the order status and shipment date after the shipment. */
	UPDATE orders SET status = IF(status="Paid", "Closed", "Received"),
		shipDate = oTransDate
	WHERE id = paramOrderKey;

  END;


//
DELIMITER ;
