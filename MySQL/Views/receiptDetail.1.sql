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
	ir.amount as appliedAmt
FROM receipt rc 
JOIN contact cust ON cust.id = rc.customerNo 
LEFT JOIN itemReceipt ir ON ir.receiptNo = rc.id 
JOIN orderItem oi ON oi.id = ir.itemNo 
JOIN orders o ON o.id = oi.orderKey 
LEFT JOIN orderReceipt orc ON orc.orderKey = o.id and orc.receiptNo = rc.id 
WHERE oi.itemStatus NOT IN ("B","X","Y")
AND o.orderType IN ("C", "R")
ORDER BY rc.id DESC; 


--select rc.id, cust.id AS customerNo, ir.itemNo, ir.titleId, ir.applyQty, oi.quantity, oi.price-oi.deduction as effPrice, o.orderNo, o.invoiceNo, orc.orderPaid 

--where rc.id = 12;


--/* FROM receipt rc 
--JOIN contact cust ON cust.id = rc.customerNo 
--LEFT JOIN itemReceipt ir ON ir.receiptNo = rc.id
--JOIN orderItem oi ON oi.id = ir.itemNo
--JOIN orders o ON o.id = oi.orderKey
--LEFT JOIN orderReceipt orc ON orc.receiptNo = rc.id AND orc.orderKey = o.id */