/*
	getInventoryStatus() will return the current inventory count for the 
		title, condition, location, and as of the item id submitted.

	THIS PROCEDURE HAS BEEN REPLACED WITH A STORED FUNCTION.

 */

DELIMITER //

CREATE OR REPLACE PROCEDURE getInventoryStatus

  (	IN paramTitleNo INTEGER,
	IN paramItemCondtn CHAR(1),
	IN paramLocation INTEGER,
	IN paramItemNo INTEGER,
	IN paramTransDate DATETIME,
	OUT returnCount DECIMAL(10,2)
  )

  READS SQL DATA
  BEGIN

	IF (paramItemCondtn IS NULL) THEN
		SET paramItemCondtn = "";
	END IF;

	IF (paramTransDate IS NULL) THEN
		SET paramTransDate = CURRENT_TIMESTAMP;
	END IF;
	
	IF (paramItemNo IS NULL) THEN
		SELECT MAX(itemNo) INTO paramItemNo FROM inventory;
	END IF;

	SELECT SUM(inv.quantity)
	INTO returnCount
	FROM inventory inv
	WHERE inv.titleNo = paramTitleNo
	  AND inv.itemCondtn = paramItemCondtn
	  AND inv.location=paramLocation
	  AND inv.itemNo < paramItemNo
	GROUP BY inv.titleId, inv.itemCondtn, inv.location;


  END;

//
DELIMITER ;
