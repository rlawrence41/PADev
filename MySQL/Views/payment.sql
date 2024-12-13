drop view payment;
create view payment AS
(select l.id,
	l.transDate,
	l.transEvent,
	l.transType,
	l.accountNo,
	concat_ws(", ",acct.lastName, acct.firstName, acct.company) as accountIdSearch, 
	l.acctType,
	l.amount,
	-l.amount AS payAmount,
	l.docNo,
	l.orderKey,
	o.orderNo, 
	l.itemNo,
	l.receiptNo,
	l.specNo,
	l.lExported,
	l.comment,
	l.updatedBy,
	l.userNo,
	l.lastUpdated
from ledger l left join contact acct ON acct.id = l.accountNo
left join orders o ON o.id = l.orderKey
where l.transEvent = "PAYMENT"
  AND l.acctType = "L");
