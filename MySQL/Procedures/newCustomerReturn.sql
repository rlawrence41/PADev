DELIMITER //

/* Create a new customer order record with a new id, invoice# and print batch#. */

CREATE OR REPLACE PROCEDURE newCustomerReturn()
  COMMENT 'Generates a new customer return with appropriate defaults.'

  MODIFIES SQL DATA 
  BEGIN

	DECLARE orderNo integer;
	DECLARE currentBatchNo integer;
	DECLARE orderDate date;
	DECLARE ownerNo integer;
	
		
	CALL newKeyReturn(orderNo);
	CALL newKeyBatchNo(currentBatchNo);
	CALL getOwnerContact(ownerNo);
	
	INSERT INTO orders (orderNo, invoiceNo, orderDate, orderType, supplierNo, status, batchNo)
		VALUES (orderNo, 0, NOW(), "R", ownerNo, "Open", currentBatchNo);
	
  END;

//
DELIMITER ;