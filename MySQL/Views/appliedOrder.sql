DROP VIEW if exists appliedOrder;
CREATE SQL SECURITY INVOKER VIEW appliedOrder AS 
SELECT 
	coalesce(orc.id, 0) as id,
	orc.id AS applied,
	uo.id AS orderKey,
	if(o.orderType="R", convert(o.orderNo,char(10)),
	concat_ws("-",convert(o.orderNo,char(10)), convert(o.invoiceNo, char(2)))) AS orderStr,
	o.orderType,
	o.orderDate,
	orc.receiptNo,
	o.customerNo,
	concat_ws(", ",cust2.lastName, cust2.firstName, cust2.company) as customerIdSearch,
	uo.orderTotal, 
	uo.orderPaid,
	uo.orderTotal - uo.orderPaid AS orderBalance,
	uo.surcharges,
	uo.srchgPaid,
	uo.surcharges - uo.srchgPaid AS srchgBalance
	FROM unpaidOrder uo
	JOIN orders o ON o.id = uo.id
	JOIN contact cust2 ON cust2.id = o.customerNo
	LEFT JOIN orderReceipt orc ON orc.orderKey = uo.id 
	ORDER BY orderKey;





SELECT coalesce(orc.id, 0) as id,
	orc.id AS applied,
	o.id AS orderKey,
	o.orderType,
	o.customerNo,
	if (o.orderType = "R", -o.total, o.total) AS total,
	orc.receiptNo,
	SUM(if(orc.receiptNo = 10, 0.00, orc.orderPaid)) AS sumPaid
FROM orders o 
LEFT JOIN orderReceipt orc ON orc.orderKey = o.id
GROUP BY o.id
HAVING total != sumPaid;