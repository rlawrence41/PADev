SELECT cir.id, cir.applied, cir.itemNo, cir.titleNo, cir.titleId, cir.orderKey, 
	cir.orderType, cir.orderStr, cir.customerNo, cir.customerIdSearch, 
	cir.receiptNo, cir.orderQty, cir.remainQty, cir.effPrice,
	ROUND(cir.effPrice * cir.remainQty, 2) AS expAmount, 
	cir.applyQty, cir.amount
FROM (
  SELECT 
	coalesce(ir.id, 0) as id,
	ir.id AS applied,
	oi.id AS itemNo,
	oi.titleNo,
	oi.titleId,
	oi.orderKey,
	o.orderType,
	if(o.orderType="R", convert(o.orderNo,char(10)),
	concat_ws("-",convert(o.orderNo,char(10)), convert(o.invoiceNo, char(2)))) AS orderStr,
	o.customerNo,
	concat_ws(", ",cust.lastName, cust.firstName, cust.company) as customerIdSearch,
	ir.receiptNo,
	if (o.orderType = "R", -oi.quantity, oi.quantity) as orderQty,
	(if(o.orderType = "R", -oi.quantity, oi.quantity)) - 
		SUM(if(ir.receiptNo = %receiptNo%, 0.00, coalesce(ir.applyQty, 0.00)))
		AS remainQty,
	oi.price - oi.deduction AS effPrice,
	coalesce(ir.applyQty, 0.00) as applyQty,
	coalesce(ir.amount, 0.00) as amount,
	SUM(coalesce(ir.applyQty, 0.00)) AS itemQty
FROM orderItem oi 
JOIN orders o ON o.id = oi.orderKey
JOIN contact cust ON cust.id = o.customerNo
LEFT JOIN itemReceipt ir ON ir.itemNo = oi.id
WHERE o.orderType IN ("C", "R")
  AND o.customerNo = %customerNo%
GROUP BY oi.id) AS cir
WHERE cir.remainQty != 0
%orderBy% %limit%;
