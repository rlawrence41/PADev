DROP VIEW if exists appliedItem;
CREATE SQL SECURITY INVOKER VIEW appliedItem AS 
select 
	coalesce(ir.id, 0) as id,
	ir.id AS applied,
	ui.id AS itemNo,
	ui.titleId,
	ui.orderKey,
	ui.orderType,
	if(ui.orderType="R", convert(ui.orderNo,char(10)),
	concat_ws("-",convert(ui.orderNo,char(10)), convert(ui.invoiceNo, char(2)))) AS orderStr,
	ui.customerNo,
	concat_ws(", ",cust2.lastName, cust2.firstName, cust2.company) as customerIdSearch,
	ir.receiptNo,
	ui.orderQty,
	ui.orderQty - ui.itemQty + coalesce(ir.applyQty, 0.00) AS remainQty,
	ui.effPrice,
	ROUND((ui.orderQty - ui.itemQty + coalesce(ir.applyQty, 0.00)) * ui.effPrice, 2) AS expAmount,
	coalesce(ir.applyQty, 0.00) as applyQty,
	coalesce(ir.amount, 0.00) as amount
from unpaidItem ui
JOIN contact cust2 ON cust2.id = ui.customerNo
LEFT JOIN itemReceipt ir ON ir.itemNo = ui.id;
