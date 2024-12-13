DROP VIEW if exists appliedOrder;
CREATE SQL SECURITY INVOKER VIEW appliedOrder AS 
(SELECT 
	applied,
	id,
	orderKey,
	orderStr,
	orderType,
	orderDate,
	customerNo,
	customerIdSearch,
	orderTotal, 
	orderPaid,
	orderBalance,
	surcharges,
	srchgPaid,
	srchgBalance
FROM unpaidOrder)
UNION
(SELECT
	orc.id AS applied,
	o.id,
	orc.orderKey,
	if(o.orderType="R", convert(o.orderNo,char(10)),
	concat_ws("-",convert(o.orderNo,char(10)), convert(o.invoiceNo, char(2)))) AS orderStr,
	o.orderType,
	o.orderDate,
	o.customerNo,
	concat_ws(", ",cust2.lastName, cust2.firstName, cust2.company) as customerIdSearch,
	o.total AS orderTotal, 
	orc.orderPaid,
	o.total - orc.orderPaid AS orderBalance,
	orc.surcharges,
	orc.srchgPaid,
	orc.surcharges - orc.srchgPaid AS srchgBalance
FROM orderReceipt orc JOIN orders o ON orc.orderKey = o.id
JOIN contact cust2 ON cust2.id = o.customerNo
WHERE orc.orderKey IN (SELECT orderKey FROM unpaidOrder));

