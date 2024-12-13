DELIMITER //

/* Deletes an orders record along with supporting resource instances. */

CREATE OR REPLACE PROCEDURE deleteReceipt
  (IN paramReceiptNo integer)
  COMMENT 'Removes a receipt and related records.'

  MODIFIES SQL DATA 
  BEGIN

	DELETE FROM inventory WHERE itemNo IN (SELECT itemNo FROM itemReceipt WHERE receiptNo = paramReceiptNo);
	DELETE FROM ledger WHERE receiptNo = paramReceiptNo AND transEvent = "RECEIPT";
	DELETE FROM itemReceipt WHERE receiptNo = paramReceiptNo;
	DELETE FROM orderReceipt WHERE receiptNo = paramReceiptNo;
	DELETE FROM receipt WHERE id = paramReceiptNo;
	
  END;

//
DELIMITER ;
