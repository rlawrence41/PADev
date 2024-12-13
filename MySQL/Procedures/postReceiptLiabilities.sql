/* 
	postReceiptLiabilities()
	
		This procedure first gathers item receipts associated with the receipt
		number submitted.  It associates the previous number sold for each item
		via the getInventoryStatus() function.  Finally, it posts the liabilities
		associated with each item receipt to the ledger by calling the
		postTitleLiabilities() procedure.

 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postReceiptLiabilities(
	IN parReceiptNo INTEGER,		-- the receipt to post liabilities for
	IN parUserNo INTEGER			-- the current user id for the trace fields
	)
  COMMENT 'Calls postTitleLiabilities() for items associated with a receipt.'


  MODIFIES SQL DATA

  BEGIN

	/* To capture item receipt details... */
	DECLARE colReceiptNo INTEGER;
	DECLARE colOrderKey INTEGER;
	DECLARE colItemNo INTEGER;
	DECLARE colTitleNo INTEGER;
	DECLARE colTitleId VARCHAR(20);
	DECLARE colTransDate DATETIME;
	DECLARE colItemQty DECIMAL(10,2);
	DECLARE colItemAmount DECIMAL(10,2);
	DECLARE colItemPrice DECIMAL(10,2);
	DECLARE colRetailPrice DECIMAL(10,2);
	DECLARE colNumberSold DECIMAL(10,2);
	DECLARE colDiscount DECIMAL(6,2);

	DECLARE done INT DEFAULT FALSE;

	/* Capture item receipt details. */
	DECLARE cur1 CURSOR FOR 
	  SELECT r.id AS receiptNo,
		oi.orderKey,
		ir.itemNo,
		ir.titleNo,
		ir.titleId,
	    r.recptDate,
		ir.applyQty,
		ir.amount,
		ROUND(ir.amount/ir.applyQty, 2) AS itemPrice,
		oi.price,
		ROUND(ir.amount/ir.applyQty/oi.price*100, 2) AS effDiscount,
		getInventoryStatus(ir.titleNo, "R", 0, "", ir.itemNo, r.recptDate) AS numberSold
	  FROM itemReceipt ir
	  JOIN receipt r ON ir.receiptNo = r.id
	  JOIN orderItem oi ON oi.id = ir.itemNo
	  WHERE r.id = parReceiptNo;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	OPEN cur1;
	read_loop: LOOP
		FETCH cur1 INTO 
			colReceiptNo,
			colOrderKey,
			colItemNo,
			colTitleNo,
			colTitleId,
			colTransDate,
			colItemQty,
			colItemAmount,
			colItemPrice,
			colRetailPrice,
			colNumberSold,
			colDiscount;


		IF done THEN
		  LEAVE read_loop;
		END IF;

		-- Sample:
		-- CALL postTitleLiabilities(12, 11, 19, 3, "DEMO 2", "2022-03-09 00:00:00", 200, 8.98, 19.95, 3, 45.01, 1);
		CALL postTitleLiabilities(
			colReceiptNo,
			colOrderKey,
			colItemNo,
			colTitleNo,
			colTitleId,
			colTransDate,
			colItemQty,
			colItemAmount,
			colItemPrice,
			colRetailPrice,
			colNumberSold,
			colDiscount,
			parUserNo
		);

	END LOOP;

	CLOSE cur1;

  END;

//
DELIMITER ;