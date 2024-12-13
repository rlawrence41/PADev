DELIMITER //

CREATE OR REPLACE PROCEDURE getItemsToPay
  (parameter_receiptNo INTEGER,
   parameter_customerNo INTEGER)
  COMMENT 'Gathers candidate items to apply funds to.'

  READS SQL DATA 
  BEGIN
  
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
		parameter_receiptNo AS receiptNo,
		ui.orderQty,
		(ui.orderQty - ui.itemQty) as remainQty,
		ui.effPrice,
		ROUND((ui.orderQty - ui.itemQty) * ui.effPrice, 2) AS expAmount,
		(ui.orderQty - ui.itemQty) AS applyQty,
		ROUND((ui.orderQty - ui.itemQty) * ui.effPrice, 2) AS amount
FROM unpaidItem ui JOIN orders o ON o.id = ui.orderKey
JOIN contact cust2 ON cust2.id = o.customerNo
	WHERE ui.customerNo = parameter_customerNo)
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
		if(o.orderType = "R", -oi.quantity, oi.quantity) AS orderQty,
		ir.remainQty,
		oi.price - oi.deduction AS effPrice,
		ROUND(ir.remainQty * (oi.price - oi.deduction), 2) as expAmount,
		ir.applyQty,
		ir.amount
FROM itemReceipt ir JOIN orderItem oi ON oi.id = ir.itemNo
JOIN orders o ON o.id = ir.orderKey
JOIN contact cust2 ON cust2.id = o.customerNo
WHERE ir.customerNo = parameter_customerNo AND ir.receiptNo = parameter_receiptNo)
ORDER BY 3;
 
  END;


CREATE OR REPLACE PROCEDURE getItemsToPayCount
  (parameter_receiptNo INTEGER,
   parameter_customerNo INTEGER)
  COMMENT 'Counts the candidate items to apply funds to.'

  READS SQL DATA
  BEGIN
    
	DECLARE count1 INTEGER;
	DECLARE count2 INTEGER;
	
	SELECT count(*) INTO count1 FROM unpaidItem 
	WHERE customerNo = parameter_customerNo;

	SELECT count(*) INTO count2 FROM itemReceipt ir
	WHERE ir.customerNo = parameter_customerNo
	  AND ir.receiptNo = parameter_receiptNo; 
	  
	SELECT (count1 + count2) AS reccount;
 
  END;

//
DELIMITER ;
