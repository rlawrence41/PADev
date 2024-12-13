DROP VIEW if exists appliedItem;
CREATE SQL SECURITY INVOKER VIEW appliedItem AS 
(SELECT NULL AS applied,
		NULL AS id,
		ui.id AS itemNo,
		ui.titleId,
		ui.orderKey,
		ui.orderType,
	if(o.orderType="R", convert(o.orderNo,char(10)),
	concat_ws("-",convert(o.orderNo,char(10)), convert(o.invoiceNo, char(2)))) AS orderStr,
		ui.customerNo,
	concat_ws(", ",cust2.lastName, cust2.firstName, cust2.company) as customerIdSearch,
		NULL AS receiptNo,
		ui.orderQty,
		(ui.orderQty - ui.itemQty) as remainQty,
		ui.effPrice,
		ROUND((ui.orderQty - ui.itemQty) * ui.effPrice, 2) AS expAmount,
		ui.itemQty AS applyQty,
		ui.itemAmount AS amount
FROM unpaidItem ui JOIN orders o ON o.id = ui.orderKey
JOIN contact cust2 ON cust2.id = o.customerNo)
UNION
(SELECT ir.id AS applied,
		ir.id,
		ir.itemNo,
		ir.titleId,
		ir.orderKey,
		o.orderType,
	if(o.orderType="R", convert(o.orderNo,char(10)),
	concat_ws("-",convert(o.orderNo,char(10)), convert(o.invoiceNo, char(2)))) AS orderStr,
		ir.customerNo,
	concat_ws(", ",cust2.lastName, cust2.firstName, cust2.company) as customerIdSearch,
		ir.receiptNo,
		oi.quantity AS orderQty,
		ir.remainQty,
		oi.price - oi.deduction AS effPrice,
		ROUND(ir.remainQty * (oi.price - oi.deduction), 2) as expAmount,
		ir.applyQty,
		ir.amount
FROM itemReceipt ir JOIN orderItem oi ON oi.id = ir.itemNo
JOIN orders o ON o.id = ir.orderKey
JOIN contact cust2 ON cust2.id = o.customerNo)