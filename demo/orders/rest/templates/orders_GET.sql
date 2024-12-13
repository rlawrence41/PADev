select orders.id, orders.orderNo, orders.invoiceNo, 
	IF(orders.orderType="C",concat(convert(orders.orderNo,char(10)), "-" , convert(orders.invoiceNo, char(2))),
		convert(orders.orderNo,char(13))) AS invoiceStr,
	orders.orderType, orders.orderDate, orders.custPONo, 
	orders.supplierNo, 
	concat_ws(", ", suppl.lastName, suppl.firstName, suppl.company) as supplierNoSearch,
	orders.customerNo, 
	concat_ws(", ", cust.lastName, cust.firstName, cust.company) as customerNoSearch,
	orders.shipToNo, 
	concat_ws(", ", shp.lastName, shp.firstName, shp.company) as shipToNoSearch,
	orders.shipToAddr, orders.lCreditCard, orders.courier,
	cu.courier as courierSearch,
	orders.shipWeight, orders.shipCharge, orders.terms, orders.termsDesc, 
	orders.salesRepNo, 
	concat_ws(", ", sr.lastName, sr.firstName, sr.company) as salesRepNoSearch,
	orders.discount, orders.lTaxable, orders.stateTaxRate, orders.lTaxShip, 
	orders.localTaxRate, orders.adjType1, orders.adjustment1,
	orders.adjType2, orders.adjustment2, orders.subtotal,
	noTaxSubtotal, stateTax, localTax, orders.total, 
	orders.status, orders.priorState, orders.shipDate, orders.batchNo, 
	orders.lProcessed, orders.comment, orders.lExported, 
	orders.updatedBy, orders.userNo, orders.lastUpdated 
	from orders  
	left join contact suppl on suppl.id = orders.supplierNo
	left join contact cust on cust.id = orders.customerNo 
	left join contact shp on shp.id = orders.shipToNo
	left join contact sr on sr.id = orders.salesRepNo
	left join courier cu on cu.id = orders.courier
	%where% %orderBy% %limit% ;
