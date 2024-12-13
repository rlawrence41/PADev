SELECT count(cor.id) AS reccount, cor.orderTotal, cor.orderPaid FROM
  (SELECT coalesce(orc.id, 0) as id,
	orc.id AS applied,
	o.id AS orderKey,
	o.orderType,
	o.orderDate,
	orc.receiptNo,
	o.customerNo,
	if(o.orderType = "R", -o.total, o.total) AS orderTotal,
	orc.orderPaid,
	orc.surcharges, 
	orc.srchgPaid,
	SUM(if(orc.receiptNo = %receiptNo%, 0.00, coalesce(orc.orderPaid, 0.00))) AS includePaid
  FROM orders o 
  JOIN contact cust ON cust.id = o.customerNo
  LEFT JOIN orderReceipt orc ON orc.orderKey = o.id
  WHERE o.orderType IN ("C", "R") 
	AND o.customerNo = %customerNo%
  GROUP BY o.id) AS cor
WHERE cor.orderTotal != cor.includePaid;
