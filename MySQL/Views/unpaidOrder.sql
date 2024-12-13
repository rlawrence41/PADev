DROP VIEW if exists unpaidOrder;
CREATE VIEW unpaidOrder AS 
SELECT o.id,
	o.orderType, 
	o.customerNo, 
	IF(o.orderType="R", -o.total, o.total) AS orderTotal, 
	SUM(coalesce(orc.orderPaid, 0.00)) AS orderPaid, 
	(o.shipCharge + o.stateTax + o.localTax + o.adjustment1 + o.adjustment2) AS surcharges, 
	SUM(coalesce(orc.srchgPaid, 0.00)) AS srchgPaid 
	FROM orders o LEFT JOIN orderReceipt orc ON orc.orderKey = o.id 
	WHERE o.orderType IN ("C", "R") 
	GROUP BY 1 
	HAVING orderPaid != orderTotal;
