DELIMITER //

CREATE OR REPLACE PROCEDURE newKeyPO
  (OUT paramLastKey integer)
  COMMENT 'Generates or updates the purchase order number key.'

  MODIFIES SQL DATA 
  BEGIN

	DECLARE lastKey1 integer;
	DECLARE lastKey2 integer;
  
	SELECT MAX(orderNo) INTO lastKey1 FROM orders WHERE orderType = "P" ;
	SELECT lastKey INTO lastKey2 FROM keyField WHERE alias = "PO" ;
	SET lastKey2 := coalesce(lastKey2, 0);
	SET paramLastKey := IF(lastkey1+1 > lastKey2, lastKey1+1, lastKey2);

	INSERT INTO keyField (resource, keyField, alias, lastKey)
	VALUES("orders", "orderNo", "PO", paramLastKey)
	ON DUPLICATE KEY UPDATE lastKey = paramLastKey;

	
  END;

//
DELIMITER ;
