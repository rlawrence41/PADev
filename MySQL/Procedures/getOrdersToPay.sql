DELIMITER //

CREATE OR REPLACE PROCEDURE getOrdersToPay
  (parameter_receiptNo INTEGER,
   parameter_customerNo INTEGER)
  COMMENT 'Gathers candidate customer orders and returns to apply funds to.'

  READS SQL DATA                      /* Data access clause */
  BEGIN
  
(SELECT 
	NULL AS applied,
	NULL AS id,
	o.id AS orderKey,
	if(o.orderType="R", convert(o.orderNo,char(10)),
	concat_ws("-",convert(o.orderNo,char(10)), convert(o.invoiceNo, char(2)))) AS orderStr,
	o.orderType,
	orderDate,
	o.customerNo,
	concat_ws(", ",cust2.lastName, cust2.firstName, cust2.company) as customerIdSearch,
	uo.orderTotal, 
	uo.orderPaid,
	uo.orderTotal - uo.orderPaid AS orderBalance,
	uo.surcharges,
	uo.srchgPaid,
	uo.surcharges - uo.srchgPaid AS srchgBalance
	FROM orders o JOIN unpaidOrder uo ON uo.orderKey = o.id 
	JOIN contact cust2 ON cust2.id = o.customerNo
	WHERE o.customerNo = parameter_customerNo)
	UNION
	(SELECT
		orc.id AS applied,
		o.id,
		orc.orderKey,
		if(o.orderType="R", convert(o.orderNo,char(10)),
		concat_ws("-",convert(o.orderNo,char(10)), convert(o.invoiceNo, char(2)))) AS orderStr,
		o.orderType,
		o.orderDate,
		o.customerNo,
		concat_ws(", ",cust2.lastName, cust2.firstName, cust2.company) as customerIdSearch,
		orc.orderTotal, 
		orc.orderPaid,
		orc.orderTotal - orc.orderPaid AS orderBalance,
		orc.surcharges,
		orc.srchgPaid,
		orc.surcharges - orc.srchgPaid AS srchgBalance
	FROM orderReceipt orc JOIN orders o ON orc.orderKey = o.id
	JOIN contact cust2 ON cust2.id = o.customerNo
	WHERE orc.customerNo = parameter_customerNo
	  AND orc.receiptNo = parameter_receiptNo
	)
	ORDER BY 3;
 
  END;


CREATE OR REPLACE PROCEDURE getOrdersToPayCount
  (parameter_receiptNo INTEGER,
   parameter_customerNo INTEGER)
  COMMENT 'Counts the candidate customer orders and returns to apply funds to.'

  READS SQL DATA                      /* Data access clause */
  BEGIN
    
	DECLARE count1 INTEGER;
	DECLARE count2 INTEGER;
	
	SELECT count(*) INTO count1 FROM unpaidOrder 
	WHERE customerNo = parameter_customerNo;

	SELECT count(*) INTO count2 FROM orderReceipt orc
	WHERE orc.customerNo = parameter_customerNo
	  AND orc.receiptNo = parameter_receiptNo; 
	  
	SELECT (count1 + count2) AS reccount;
 
  END;

//
DELIMITER ;
