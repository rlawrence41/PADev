DROP VIEW if exists unpaidItem;
CREATE SQL SECURITY INVOKER VIEW unpaidItem AS 
select oi.id,
		oi.orderKey,
		o.orderNo,
		o.invoiceNo,
		o.orderType,
		o.customerNo,
		oi.titleId,
		if(o.orderType="R", -oi.quantity, oi.quantity) AS orderQty,
		(oi.price - oi.deduction) as effPrice,
		sum(coalesce(ir.applyQty, 0.00)) as itemQty,
		sum(coalesce(ir.amount, 0.00)) as itemAmount
FROM orderItem oi 
JOIN orders o ON o.id = oi.orderKey
LEFT JOIN itemReceipt ir ON ir.itemNo = oi.id
WHERE o.orderType IN ("C", "R")
GROUP BY 1;

--HAVING itemQty != orderQty);
