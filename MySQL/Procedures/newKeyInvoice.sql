DELIMITER //

CREATE OR REPLACE PROCEDURE newKeyInvoice
  (OUT paramLastKey integer)
  COMMENT 'Generates or updates the invoice number key for a customer order.'

  MODIFIES SQL DATA 
  BEGIN

	DECLARE lastKey1 integer;
	DECLARE lastKey2 integer;
  
	SELECT MAX(orderNo) INTO lastKey1 FROM orders WHERE orderType = "C" ;
	SELECT lastKey INTO lastKey2 FROM keyField WHERE alias = "invoice" ;
	SET lastKey2 := coalesce(lastKey2, 0);
	SET paramLastKey := IF(lastkey1+1 > lastKey2, lastKey1+1, lastKey2);

	INSERT INTO keyField (resource, keyField, alias, lastKey)
	VALUES("orders", "orderNo", "invoice", paramLastKey)
	ON DUPLICATE KEY UPDATE lastKey = paramLastKey;

	
  END;

//
DELIMITER ;
