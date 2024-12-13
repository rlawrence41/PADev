/* This procedure will generate and store itemReceipts for the receiptNo submitted
	based on the associated order receipts.  It is called after the Apply To Orders
	wizard step for the receipt transaction.
	
	The assumption here is that items will be completely paid off for the selected 
	order receipts.
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postItemReceipts
  (paramReceiptNo INTEGER,
   paramUpdatedBy varchar(20),					/*	auth.user_id	*/
   paramUserNo integer(10),						/*	auth.id			*/
   paramLastUpdated dateTime)					/*	sqlNow()		*/
  COMMENT 'Generates item receipts for a receipt transaction.'


  MODIFIES SQL DATA                   /* Data access clause */
  BEGIN
  
	/* Delete any previously logged item receipts for the submitted receipt.
	
		Note that postOrderReceipts takes a more conservative approach.  The 
		problem with replicating the approach of updating existing item receipts
		is that the logic becomes quite complicated.  Since the purpose here is
		to pay off ALL ordered items for the selected orders and returns, it 
		seems faster and cleaner to start from scratch.
		
	 */
	 
	 DELETE FROM itemReceipt WHERE receiptNo = paramReceiptNo;

	/* Insert new item receipts for the selected orders.					 */

	INSERT INTO itemReceipt (
		itemNo,
		titleNo,
		titleId,
		orderKey,
		customerNo,
		receiptNo,
		remainQty,
		applyQty,
		amount,
		updatedBy,
		userNo,
		lastUpdated
		)
	  SELECT
		oi.id,
		oi.titleNo,
		oi.titleId,
		oi.orderKey,
		orc.customerNo,
		orc.receiptNo,
		if(o.orderType = "R", -oi.quantity, oi.quantity),	/* remainQty  */
		if(o.orderType = "R", -oi.quantity, oi.quantity),	/* applyQty */
		(if(o.orderType = "R", -oi.quantity, oi.quantity)*(oi.price-oi.deduction)), /* amount */
		paramUpdatedBy,
		paramUserNo,
		paramLastUpdated
		FROM orderReceipt orc
		JOIN orderItem oi ON oi.orderKey = orc.orderKey
		JOIN orders o ON o.id = oi.orderKey
		WHERE orc.receiptNo = paramReceiptNo;
 
  END;

//
DELIMITER ;
