DELIMITER //

/* Create a new customer order record with a new id, invoice# and print batch#. */

CREATE OR REPLACE PROCEDURE newPurchaseOrder()
  COMMENT 'Generates a new purchase order with appropriate defaults.'

  MODIFIES SQL DATA 
  BEGIN

	DECLARE orderNo integer;
	DECLARE currentBatchNo integer;
	DECLARE orderDate date;
	DECLARE ownerNo integer;
	
		
	CALL newKeyPO(orderNo);
	CALL newKeyBatchNo(currentBatchNo);
	CALL getOwnerContact(ownerNo);
	
	INSERT INTO orders (orderNo, orderDate, orderType, customerNo, shipToNo, status, batchNo)
		VALUES (orderNo, NOW(), "P", ownerNo, ownerNo, "Open", currentBatchNo);
	
  END;

//
DELIMITER ;
