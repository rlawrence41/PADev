DELIMITER //

CREATE OR REPLACE PROCEDURE newKeyBatchNo
  (OUT paramLastKey integer)
  COMMENT 'Generates or updates the print batch number key.'

  MODIFIES SQL DATA 
  BEGIN

	DECLARE lastKey1 integer;
	DECLARE lastKey2 integer;
  
	SELECT MAX(batchNo) INTO lastKey1 FROM orders WHERE orderType = "C" ;
	SELECT lastKey INTO lastKey2 FROM keyField WHERE alias = "batchNo" ;
	SET lastKey2 := coalesce(lastKey2, 0);
	SET paramLastKey := IF(lastkey1+1 > lastKey2, lastKey1+1, lastKey2);

	INSERT INTO keyField (resource, keyField, alias, lastKey)
	VALUES("orders", "batchNo", "batchNo", paramLastKey)
	ON DUPLICATE KEY UPDATE keyField = "batchNo", lastKey = paramLastKey;

	
  END;

//
DELIMITER ;
