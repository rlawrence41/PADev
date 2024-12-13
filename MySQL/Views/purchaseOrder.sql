DROP VIEW if exists purchaseOrder;
CREATE SQL SECURITY INVOKER VIEW purchaseOrder AS 
SELECT ordr.id as id, ordr.orderNo,
	ordr.orderType, ordr.orderDate, ordr.custPONo, 
	ordr.supplierNo, 
	concat_ws(", ",suppl.lastName, suppl.firstName, suppl.company) as supplierIdSearch,
	ordr.customerNo, 
	concat_ws(", ",cust.lastName, cust.firstName, cust.company) as customerIdSearch,
	ordr.shipToNo, 
	concat_ws(", ",shp.lastName, shp.firstName, shp.company) as shipToIdSearch,
	ordr.shipToAddr, ordr.lCreditCard, ordr.courier, cour.courier as courierSearch, 
	ordr.shipWeight as orderWeight, ordr.shipCharge, ordr.terms, ordr.termsDesc, 
	ordr.salesRepNo, 
	concat_ws(", ",sr.lastName, sr.firstName, sr.company) as salesRepIdSearch,
	ordr.discount AS orderDiscount, 
	ordr.lTaxable, if(ordr.lTaxable, 'yes', 'no') AS lTaxableStr,
	ordr.stateTaxRate, 
	ordr.lTaxShip, if(ordr.lTaxShip, 'yes', 'no') AS lTaxShipStr,
	ordr.localTaxRate, ordr.adjType1, ordr.adjustment1,
	ordr.adjType2, ordr.adjustment2, ordr.subtotal, 
	ordr.noTaxSubtotal, ordr.stateTax, ordr.localTax, ordr.total, ordr.status, 
	ordr.priorState, ordr.shipDate, ordr.batchNo, 
	ordr.lProcessed, ordr.comment, ordr.lExported, 
	ordr.updatedBy, ordr.userNo, ordr.lastUpdated, 
	item.id as itemId, item.orderKey, item.titleNo, item.titleId, item.title, 
	item.lInventory, item.lConsignment, item.itemCondtn, item.quantity, 
	item.orderDate as itemDate, 
	item.price, item.discount, item.deduction, 
	item.shipWeight, 
	item.itemStatus, item.lSubscript, item.expireDate, 
	if(instr("BXY", item.itemStatus)> 0, 00000.00, ROUND((item.quantity * (item.price - item.deduction)),2)) as extPrice,
	if(instr("BXY", item.itemStatus)> 0, 00000.00, ROUND((item.quantity * item.shipWeight),2)) as extWeight,
	item.comment as itemComment 
  FROM orders ordr LEFT JOIN orderItem item ON item.orderKey = ordr.id 
	LEFT JOIN contact suppl ON suppl.id = ordr.supplierNo
	LEFT JOIN contact cust ON cust.id = ordr.customerNo 
	LEFT JOIN contact shp ON shp.id = ordr.shipToNo
	LEFT JOIN contact sr ON sr.id = ordr.salesRepNo
	LEFT JOIN courier cour ON cour.id = ordr.courier
  WHERE ordr.orderType = "P"
  ORDER BY ordr.id DESC ;

	