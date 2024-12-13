select contact.id, contact.contactId, contact.company, contact.namePrefix, 
contact.firstName, contact.midName, contact.lastName, contact.nameSuffix, 
contact.poAddr, contact.courAddr, contact.city, contact.stateAbbr, contact.country, 
contact.zipCode, contact.munAbbr, contact.billTo, contact.phone, contact.phone2, 
contact.email, contact.webUrl, contact.webservice, contact.fedIdNo, contact.san, 
contact.pubnetId, contact.buyerId, contact.sellerId, contact.lExclude, 
contact.biography, contact.portrait, contact.comment, contact.password, 
contact.lAuthor, contact.lCustomer, contact.lMailList, contact.lSalesRep, 
contact.lSupplier, contact.lWarehouse, contact.lEmployee, contact.lApproved, 
contact.updatedBy, contact.userNo, contact.lastUpdated, sum(l.amount) as balance 
from contact join ledger l on l.accountNo = contact.id 
%where%
group by contact.id
%orderBy% %limit% ;