DELIMITER //

/* Deletes an orders record along with supporting resource instances. */

CREATE OR REPLACE PROCEDURE deletePurchaseOrder
  (IN paramOrderKey integer)
  COMMENT 'Removes a purchase order and related records.'

  MODIFIES SQL DATA 
  BEGIN

	DELETE FROM inventory WHERE itemNo IN (SELECT id FROM orderItem WHERE orderKey = paramOrderKey);
	DELETE FROM ledger WHERE orderKey = paramOrderKey AND transEvent = "PO";
	DELETE FROM orderItem WHERE orderKey = paramOrderKey;
	DELETE FROM orders WHERE id = paramOrderKey;
	
  END;

//
DELIMITER ;
