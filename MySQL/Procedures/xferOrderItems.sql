/* 

	This procedure will post inventory transactions for the submitted order
	to transfer items from the supplier to ship to location.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE xferOrderItems
  (paramOrderKey INTEGER)			/* Id for the transfer order */
  COMMENT 'Posts inventory transactions for the submitted transfer order.'

  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN
  `transfer`:
  
  DECLARE oType CHAR(1);
  DECLARE oTransDate DATETIME;

	/* Make sure we are processing a transfer order. */
	select orderType INTO oType from orders WHERE id = paramOrderKey;
	if (oType != "T") then
		LEAVE `transfer`;
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
		invStatus,
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
		"I",
		o.supplierNo,
		-oi.quantity,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o JOIN orderItem oi ON oi.orderKey = o.id
	  WHERE o.id = paramOrderKey
		AND oi.itemStatus NOT IN ("A","B","X","Y"));


/*	Transfer the ordered items to the ship to contact location.	*/
	INSERT INTO inventory (
		transDate,
		itemNo,
		titleNo,
		titleId,
		itemCondtn,
		invStatus,
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
		"I",
		o.shipToNo,
		oi.quantity,
		o.updatedBy,
		o.userNo,
		now() AS lastUpdated
	  FROM orders o JOIN orderItem oi ON oi.orderKey = o.id
	  WHERE o.id = paramOrderKey
		AND oi.itemStatus NOT IN ("A","B","X","Y"));

		
/* Set the order status based on the shipment. */
	UPDATE orders SET status = IF(status="Open", "Shipped", "Closed")
	WHERE id = paramOrderKey;

  END;

//
DELIMITER ;
