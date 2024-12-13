select count(*) AS reccount from 
(SELECT 
	coalesce(ir.id, 0) as id,
	ir.id AS applied,
	oi.id AS itemNo,
	oi.titleId,
	oi.orderKey,
	o.orderType,
	o.customerNo,
	ir.receiptNo,
	if (o.orderType = "R", -oi.quantity, oi.quantity) as orderQty,
	(if(o.orderType = "R", -oi.quantity, oi.quantity)) - 
		SUM(if(ir.receiptNo = %receiptNo%, 0.00, coalesce(ir.applyQty, 0.00)))
		AS remainQty,
	oi.price - oi.deduction AS effPrice,
	coalesce(ir.applyQty, 0.00) as applyQty,
	coalesce(ir.amount, 0.00) as amount
FROM orderItem oi 
JOIN orders o ON o.id = oi.orderKey
JOIN contact cust ON cust.id = o.customerNo
LEFT JOIN itemReceipt ir ON ir.itemNo = oi.id
WHERE o.orderType IN ("C", "R")
  AND o.customerNo = %customerNo%
GROUP BY oi.id) AS cir
WHERE cir.remainQty != 0
;
