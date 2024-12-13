drop table orderReceipt;
create table orderReceipt ( 
	id			integer(10) primary key auto_increment,
	orderKey   	integer(10), 
	receiptNo   integer(10), 
	customerNo  integer(10), 
	ordertotal  decimal(10, 2) NOT NULL DEFAULT 0.00, 
	orderPaid  	decimal(10, 2) NOT NULL DEFAULT 0.00, 
	surcharges  decimal(10, 2) NOT NULL DEFAULT 0.00, 
	srchgPaid  	decimal(10, 2) NOT NULL DEFAULT 0.00, 
	unapplied   decimal(10, 2) NOT NULL DEFAULT 0.00, 
	updatedBy 	varchar(20), 
	userNo		integer(10),
	lastUpdated datetime,
	index (orderKey), 
	index (receiptNo)) ;