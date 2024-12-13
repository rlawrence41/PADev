/*
	getItemDetails() gathers the item receipts for the submitted receipt, and 
					associates the number sold as of each item found.

 */

DELIMITER //

CREATE OR REPLACE PROCEDURE getItemDetails(IN parReceiptNo INTEGER)
  COMMENT 'Captures item receipt details for calculating liabilities.'

  READS SQL DATA

  BEGIN

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

  END;

//
DELIMITER ;