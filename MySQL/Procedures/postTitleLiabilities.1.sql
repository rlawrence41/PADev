/* 

	This procedure will post financial liability transactions for the submitted 
	receipt.  
	
	This will primarily be royalties, but there is very little difference
	between royalties and consignment costs.  Thus, these specifications were 
	combined into TitleLiability.  There may be other kinds of liability 
	transaction types.  e.g. Cardinal refers to their royalties as "Earnings".
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postTitleLiabilities
  (paramReceiptNo INTEGER)			/* Id for the receipt */
  COMMENT 'Posts liabilities to the ledger for an item receipt.'

  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN

	WITH itemDetail AS (
	  SELECT ir.id,
		ir.itemNo,
		ir.titleNo,
		r.id AS receiptNo,
	    r.recptDate,
		ir.applyQty,
		ir.amount AS paidAmt,
		ROUND(ir.amount/ir.applyQty/oi.price*100, 2) AS effDiscount,
		oi.price,
		oi.orderKey,
		getInventoryStatus(ir.titleNo, "R", 0, "", ir.itemNo, r.recptDate) AS numberSold
	  FROM itemReceipt ir
	  JOIN receipt r ON ir.receiptNo = r.id
	  JOIN orderItem oi ON oi.id = ir.itemNo
	  WHERE r.id = paramReceiptNo
	)
	SELECT itd.id,
	    itd.recptDate AS transDate,
		"RECEIPT" AS transEvent,
		tls.transType,
		tls.accountNo,		
		"L" AS acctType,
		ROUND((if (tls.net, itd.paidAmt, itd.price*itd.applyQty)) * tls.rate/100, 2) AS amount,
		itd.orderKey,
		itd.itemNo,
		itd.receiptNo,
		itd.applyQty,
		itd.paidAmt,
		itd.price,
		itd.numberSold,
	--	itd.effDiscount,
		tls.threshold,
	--	tls.discount,
		tls.net,
		tls.rate		
	FROM itemDetail itd
	JOIN titleLiabilitySp tls ON tls.titleNo = itd.titleNo
	  AND (tls.startDate IS NULL OR tls.startDate <= itd.recptDate)
	  AND tls.threshold <= itd.numberSold
	  AND tls.discount <= itd.effdiscount
	  AND !whenShipped
	GROUP BY itd.id, tls.accountNo;



  END;

//
DELIMITER ;
