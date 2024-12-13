select count(*) AS reccount FROM
	((SELECT orderKey FROM unpaidOrder
	WHERE customerNo = 11)
	UNION ALL
	(SELECT orc.orderKey FROM orderReceipt orc 
	WHERE orc.orderKey IN (SELECT orderKey FROM unpaidOrder)
	  AND orc.customerNo = 11
	  AND orc.receiptNo = 12));