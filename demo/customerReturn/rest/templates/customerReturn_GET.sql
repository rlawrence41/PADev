select id, orderNo, invoiceNo, orderType, orderDate, custPONo, supplierNo, supplierIdSearch, customerNo, customerIdSearch, shipToNo, shipToIdSearch, shipToAddr, lCreditCard, courier, courierSearch, orderWeight, shipCharge, terms, termsDesc, salesRepNo, salesRepIdSearch, orderDiscount, lTaxable, lTaxableStr, stateTaxRate, lTaxShip, lTaxShipStr, localTaxRate, adjType1, adjustment1, adjType2, adjustment2, subtotal, noTaxSubtotal, stateTax, localTax, total, status, priorState, shipDate, batchNo, lProcessed, comment, lExported, updatedBy, userNo, lastUpdated, itemId, orderKey, titleNo, titleId, title, lInventory, lConsignment, itemCondtn, quantity, itemDate, price, discount, deduction, shipWeight, itemStatus, lSubscript, expireDate, extPrice, extWeight, itemComment from %resource% %where% %orderBy% %limit% ;