DELIMITER //

/* Create a new customer order record with a new id, invoice# and print batch#. */

CREATE OR REPLACE PROCEDURE newReceipt()
  COMMENT 'Generates a new customer receipt with appropriate defaults.'

  MODIFIES SQL DATA 
  BEGIN
	
	INSERT INTO receipt (recptDate) VALUES (NOW());

  END;

//
DELIMITER ;
