SELECT * FROM (
  SELECT coalesce(orc.id, 0) as id,
	orc.id AS applied,
	o.id AS orderKey,
	if(o.orderType="R", convert(o.orderNo,char(10)),
	concat_ws("-",convert(o.orderNo,char(10)), convert(o.invoiceNo, char(2)))) AS orderStr,
	o.orderType,
	o.orderDate,
	orc.receiptNo,
	o.customerNo,
	concat_ws(", ",cust.lastName, cust.firstName, cust.company) as customerIdSearch,
	if (o.orderType = "R", -o.total, o.total) AS orderTotal,
	SUM(coalesce(orc.orderPaid, 0.00)) AS orderPaid,
	(if(o.orderType = "R", -o.total, o.total)) - SUM(coalesce(orc.orderPaid, 0.00)) AS orderBalance,
	(o.shipCharge + o.stateTax + o.localTax + o.adjustment1 + o.adjustment2) AS surcharges, 
	SUM(coalesce(orc.srchgPaid, 0.00)) AS srchgPaid,
	(o.shipCharge + o.stateTax + o.localTax + o.adjustment1 + o.adjustment2) - SUM(coalesce(orc.srchgPaid, 0.00)) AS srchgBalance,
	SUM(if(orc.receiptNo = %receiptNo%, 0.00, coalesce(orc.orderPaid, 0.00))) AS includePaid	
  FROM orders o 
  JOIN contact cust ON cust.id = o.customerNo
  LEFT JOIN orderReceipt orc ON orc.orderKey = o.id
  WHERE o.orderType IN ("C", "R") 
	AND o.customerNo = %customerNo%
  GROUP BY o.id) AS cor
WHERE cor.orderTotal != cor.includePaid
%orderBy% %limit%;

  