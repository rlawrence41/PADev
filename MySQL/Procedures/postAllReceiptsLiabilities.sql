DELIMITER //

CREATE OR REPLACE PROCEDURE postAllReceiptsLiabilities(
  IN parUserNo INTEGER -- the current user id for the trace fields
  )
  COMMENT 'Calls postReceiptLiabilities() for all receipts.  Use to initialize title liabilities.'

MODIFIES SQL DATA
BEGIN
	DECLARE done INT DEFAULT FALSE;
	DECLARE curReceiptNo INTEGER;

	-- Cursor to loop through all receipts
	DECLARE cur1 CURSOR FOR 
	SELECT id FROM receipt;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	OPEN cur1;
	read_loop: LOOP
		FETCH cur1 INTO curReceiptNo;

		IF done THEN
			LEAVE read_loop;
		END IF;

		-- Call the postReceiptLiabilities procedure for each receipt
		CALL postReceiptLiabilities(curReceiptNo, parUserNo);

	END LOOP;

	CLOSE cur1;

END //

DELIMITER ;
