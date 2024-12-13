SELECT itemReceipt.id, itemReceipt.itemNo, itemReceipt.titleNo, itemReceipt.titleId, 
itemReceipt.orderKey, itemReceipt.customerNo, itemReceipt.receiptNo, 
itemReceipt.remainQty, itemReceipt.applyQty, itemReceipt.amount, itemReceipt.updatedBy, 
itemReceipt.userNo, itemReceipt.lastUpdated, oi.price - oi.deduction AS effPrice,
ROUND((oi.price - oi.deduction) * itemReceipt.remainQty, 2) AS expAmount
FROM itemReceipt JOIN orderItem oi ON itemReceipt.itemNo = oi.id
%where% %orderBy% %limit% ;
