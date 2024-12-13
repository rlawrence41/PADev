DROP VIEW if exists customerOrder;
CREATE SQL SECURITY INVOKER VIEW customerOrder AS 
SELECT ordr.id, ordr.orderNo, ordr.invoiceNo, 
	IF(ordr.orderType="C",concat(convert(ordr.orderNo,char(10)), "-" , convert(ordr.invoiceNo, char(2))),
		convert(ordr.orderNo,char(13))) AS invoiceStr,
	ordr.orderType, ordr.orderDate, ordr.custPONo, 
	ordr.supplierNo, 
	concat(suppl.lastName, ", ", suppl.firstName, ", ", suppl.company) as supplierIdSearch,
	ordr.customerNo, 
	concat(cust.lastName, ", ", cust.firstName, ", ", cust.company) as customerIdSearch,
	ordr.shipToNo, 
	concat(shp.lastName, ", ", shp.firstName, ", ", shp.company) as shipToIdSearch,
	ordr.shipToAddr, ordr.lCreditCard, ordr.courier, ordr.shipWeight, ordr.shipCharge, ordr.terms, ordr.termsDesc, 
	ordr.salesRepNo, 
	concat(sr.lastName, ", ", sr.firstName, ", ", sr.company) as salesRepIdSearch,
	ordr.discount, ordr.lTaxable, ordr.stateTaxRate, ordr.lTaxShip, ordr.localTaxRate, ordr.adjType1, ordr.adjustment1,
	ordr.adjType2, ordr.adjustment2, ordr.subtotal, ordr.total, ordr.status, ordr.priorState, ordr.shipDate, ordr.batchNo, 
	ordr.lProcessed, ordr.comment, ordr.lExported, 
	ordr.updatedBy, ordr.userNo, ordr.lastUpdated, 
	item.id, item.orderKey, item.titleNo, item.titleId, item.title, 
	item.lInventory, item.lConsignment, item.itemCondtn, item.quantity, 
	item.orderDate, item.price, item.discount, item.deduction, item.shipWeight, 
	item.itemStatus, item.lSubscript, item.expireDate, item.comment, 
	item.updatedBy, item.userNo, item.lastUpdated
  FROM orders ordr JOIN orderItem item ON item.orderKey = ordr.id ;
	LEFT JOIN contact suppl ON suppl.id = ordr.supplierNo
	LEFT JOIN contact cust ON cust.id = ordr.customerNo 
	LEFT JOIN contact shp ON shp.id = ordr.shipToNo
	LEFT JOIN contact sr ON sr.id = ordr.salesRepNo
  WHERE ordr.orderType = "C";

	