SELECT ord.customerNo, oi.id, oi.orderKey, oi.titleId, oi.quantity, 
(oi.quantity * (oi.price - oi.deduction)) AS expAmount, 
SUM(ir.applyQty) as applyQty, 
SUM(ir.amount) AS appliedAmt 
FROM orderItem oi 
join orders ord on ord.id = oi.orderKey
left join itemReceipt ir ON ir.itemNo = oi.id
where oi.itemStatus NOT IN ("B","X","Y")
and ord.orderType = "C"
and ord.customerNo = 11
group by oi.id
having applyQty < quantity


SELECT ord.customerNo, oi.id, oi.orderKey, oi.titleId, -oi.quantity AS quantity, 
(-oi.quantity * (oi.price - oi.deduction)) AS expAmount, 
SUM(-ir.applyQty) as applyQty, 
SUM(-ir.amount) AS appliedAmt 
FROM orderItem oi 
join orders ord on ord.id = oi.orderKey
left join itemReceipt ir ON ir.itemNo = oi.id
where oi.itemStatus NOT IN ("B","X","Y")
and ord.orderType = "R"
and ord.customerNo = 11
group by oi.id
having applyQty < quantity


-- This seems to work for order receipts without duplicates.
-- Missing item receipts will cause orders to drop out.

select rc.id, rc.amount, cust.company, orc.id as ordCount, orc.orderPaid as ordPaid, ir.id as
item, ir.amount as itemPaid 
from receipt rc 
JOIN contact cust on cust.id = rc.customerNo 
LEFT JOIN orderReceipt orc ON orc.receiptNo = rc.id 
LEFT JOIN itemReceipt ir ON ir.receiptNo = rc.id
LEFT JOIN orderItem oi ON oi.id = ir.itemNo 
JOIN orders ord ON ord.id = oi.orderKey AND ord.id = orc.orderKey; 

