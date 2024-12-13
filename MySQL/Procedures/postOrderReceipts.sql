/* This procedure will generate and store orderReceipts for the receiptNo submitted
	based on the associated item receipts.  It is called after the Apply To Items
	wizard step for the receipt transaction.
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postOrderReceipts
  (paramReceiptNo INTEGER,
   paramUpdatedBy varchar(20),					/*	auth.user_id	*/
   paramUserNo integer(10),						/*	auth.id			*/
   paramLastUpdated dateTime)					/*	sqlNow()		*/
  COMMENT 'Generates order receipts for a receipt transaction.'


  MODIFIES SQL DATA                   /* Data access clause */
  BEGIN


		/* Be sure to remove any order receipts that no longer have item receipts. */
		
		/* Note that the following query does NOT work.  The alias doesn't work
			despite its inclusion in the Mariadb documentation for the DELETE
			query.
		
		DELETE FROM orderReceipt orc 
		WHERE orc.receiptNo = paramReceiptNo
		  AND orc.orderKey NOT IN (SELECT ir.orderKey FROM itemReceipt ir 
			WHERE ir.receiptNo = orc.receiptNo);
			
		 */

		DELETE FROM orderReceipt WHERE receiptNo = paramReceiptNo
		  AND orderKey NOT IN (SELECT ir.orderKey FROM itemReceipt ir 
			WHERE ir.receiptNo = paramReceiptNo);

  
	/* Update existing order receipts with new item totals. */

	UPDATE orderReceipt orc 
	SET orc.orderPaid = orc.srchgPaid + 
	  (SELECT SUM(coalesce(ir.amount, 0.00)) AS itemAmount
		FROM itemReceipt ir 
		WHERE ir.orderKey = orc.orderKey 
		  AND ir.receiptNo = orc.receiptNo),
	  updatedBy = paramUpdatedBy,
	  userNo = paramUserNo,
	  lastUpdated = paramLastUpdated
	WHERE orc.receiptNo = paramReceiptNo;

	/* Insert new order receipts for item receipts without an existing order 
		receipt. 
	 */

	INSERT INTO orderReceipt
	   (orderKey, 
		receiptNo, 
		customerNo, 
		ordertotal, 
		orderPaid,
		surcharges,
		srchgPaid,
		updatedBy,
		userNo,
		lastUpdated)
	   SELECT 
			ir.orderKey,
			ir.receiptNo,
			rc.customerNo,
			o.total AS orderTotal,
			sum(ir.amount) AS orderPaid,
			(o.shipCharge + o.stateTax + o.localTax + o.adjustment1 + o.adjustment2) as surcharges,
			0000000.00 AS srchgPaid,
			paramUpdatedBy,
			paramUserNo,
			paramLastUpdated 
		FROM itemReceipt ir
		JOIN receipt rc ON rc.id = ir.receiptNo
		JOIN orders o ON o.id = ir.orderKey
		WHERE ir.receiptNo = paramReceiptNo
		  AND ir.orderKey NOT IN 
			(SELECT orc.orderKey FROM orderReceipt orc 
			WHERE orc.orderKey = ir.orderKey 
			  AND orc.receiptNo = ir.receiptNo)
		GROUP BY ir.orderKey;

 
  END;

//
DELIMITER ;
