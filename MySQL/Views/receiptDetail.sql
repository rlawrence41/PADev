DROP VIEW if exists receiptDetail;
CREATE SQL SECURITY INVOKER VIEW receiptDetail AS 
SELECT rc.id, 
	rc.customerNo, 
	concat_ws(", ", cust.lastName, cust.firstName, cust.company) as customerNoSearch,
	rc.recptDate, 
	oi.orderKey, 
	if(o.orderType="R",convert(o.orderNo,char(10)), concat_ws("-",convert(o.orderNo,char(10)), convert(o.invoiceNo, char(2)))) AS orderStr,
	o.orderType,
	rc.amount, 
	rc.lFullPaymnt, 
	rc.recptType, 
	rc.crcrdAcct, 
	rc.crcrdExpDt, 
	rc.crcrdVCode, 
	rc.crcrdAuth, 
	rc.transactId, 
	rc.lItemized, 
	rc.lRefund, 
	rc.lProcessed, 
	rc.lExported, 
	rc.comment, 
	rc.updatedBy, 
	rc.userNo, 
	rc.lastUpdated,
	ir.id AS itemRcptId, 
	ir.itemNo,
	oi.titleId,
	if(o.orderType="R", -oi.quantity, oi.quantity) AS orderQty,
	oi.price - oi.deduction as effPrice,
	ir.remainQty, 
	ROUND((ir.remainQty * (oi.price - oi.deduction)), 2) as expAmount,
	ir.applyQty, 
	ir.amount as appliedAmt,
	orc.surcharges,
	orc.srchgPaid
FROM receipt rc 
LEFT JOIN contact cust ON cust.id = rc.customerNo 
LEFT JOIN itemReceipt ir ON ir.receiptNo = rc.id 
LEFT JOIN orderItem oi ON oi.id = ir.itemNo 
LEFT JOIN orders o ON o.id = oi.orderKey 
LEFT JOIN orderReceipt orc ON orc.orderKey = o.id and orc.receiptNo = rc.id 
ORDER BY rc.id DESC; 

--WHERE oi.itemStatus NOT IN ("B","X","Y")
--AND o.orderType IN ("C", "R")