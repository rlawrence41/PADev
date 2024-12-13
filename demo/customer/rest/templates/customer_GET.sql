SELECT customer.id, customer.customerNo, 
concat_ws(",", c.lastName, c.firstName, c.company) AS customerNoSearch,
customer.crcrdVndr, customer.crcrdAcct, customer.crcrdexpdt, 
customer.salesRepNo, 
concat_ws(",", sr.lastName, sr.firstName, sr.company) AS salesRepNoSearch,
customer.taxExempt, customer.discount, customer.terms, customer.termsDesc, 
customer.credLimit, customer.updatedBy, customer.userNo, customer.lastUpdated 
FROM customer JOIN contact c ON c.id = customer.customerNo
LEFT JOIN contact sr ON sr.id = customer.salesRepNo 
%where% %orderBy% %limit% ;
