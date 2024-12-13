/* 

	This procedure will REMOVE the inventory transactions for the submitted order
	to return the items to their previous state.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE unshipItems
  (paramOrderKey INTEGER)			/* Id for the order */
  COMMENT 'Removes inventory transactions for the submitted order.'

  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN
  
	/* Delete any previously logged inventory transactions for the ordered items. */	 
	DELETE FROM inventory WHERE itemNo IN (SELECT id from orderItem WHERE orderKey = paramOrderKey);
	 
	/* Reset item states */
	UPDATE orderItem SET itemStatus = NULL WHERE orderKey = paramOrderKey;


  END;

//
DELIMITER ;
