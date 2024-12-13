DROP VIEW if exists receiptSummary;
CREATE SQL SECURITY INVOKER VIEW receiptSummary AS 
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
	count(o.id) AS orderCount,
	sum(orc.orderPaid) AS orderPaid,
	count(ir.id) AS itemCount,
	sum(ir.applyQty) AS itemQty, 
	sum(ir.amount) AS itemPaid
FROM receipt rc 
JOIN contact cust ON cust.id = rc.customerNo 
LEFT JOIN itemReceipt ir ON ir.receiptNo = rc.id 
LEFT JOIN orderItem oi ON oi.id = ir.itemNo 
LEFT JOIN orders o ON o.id = oi.orderKey
LEFT JOIN orderReceipt orc ON orc.receiptNo = rc.id
GROUP BY rc.id
ORDER BY id DESC; 

--WHERE oi.itemStatus NOT IN ("B","X","Y")
--AND o.orderType IN ("C", "R")
