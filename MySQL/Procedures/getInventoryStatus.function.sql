/*
	getInventoryStatus() will return the current inventory count for the 
		title, condition, location, and as of the item id submitted.

 */

DELIMITER //

CREATE OR REPLACE FUNCTION getInventoryStatus
  (	IN paramTitleNo INTEGER,
	IN paramInvState CHAR(1),
	IN paramLocation INTEGER,
	IN paramItemCondtn CHAR(1),
	IN paramItemNo INTEGER,
	IN paramTransDate DATETIME
  ) RETURNS DECIMAL(10,2)
  COMMENT 'Returns the inventory count for the submitted title, condition and location.'

  BEGIN
	DECLARE returnCount DECIMAL(10,2);

	IF (paramInvState IS NULL) THEN
		SET paramInvState = "I";						/* Available Inventory */
	END IF;

	IF (paramTransDate IS NULL) THEN
		SET paramTransDate = CURRENT_TIMESTAMP;			/*	Now()!  */
	END IF;
	
	IF (paramItemCondtn IS NULL) THEN
		SET paramItemCondtn = "";						/* Good condition */
	END IF;

	IF (paramItemNo IS NULL) THEN
		SELECT MAX(itemNo) INTO paramItemNo FROM inventory;	/* The last item moved. */
	END IF;

	SELECT SUM(inv.quantity)
	INTO returnCount
	FROM inventory inv
	WHERE inv.titleNo = paramTitleNo
	  AND inv.itemCondtn = paramItemCondtn
	  AND inv.invState = paramInvState
	  AND inv.location=paramLocation
	  AND inv.itemNo < paramItemNo
	GROUP BY inv.titleId, inv.invState, inv.location, inv.itemCondtn;

	RETURN COALESCE(returnCount, 0) ;		/* Avoid returning NULL */
	
  END;

//
DELIMITER ;