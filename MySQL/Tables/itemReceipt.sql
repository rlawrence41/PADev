
	drop table itemReceipt;
	create table itemReceipt ( 
		id			integer(10) primary key auto_increment,
		itemNo      integer(10),
		titleNo		integer(10),
		titleId		varchar(20),
		orderKey	integer(10),
		customerNo	integer(10),
		receiptNo   integer(10), 
		remainQty  	decimal(12, 4), 
		applyQty    decimal(10, 2), 
		amount      decimal(10, 2), 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime,
		index (itemNo),
		index (orderKey),
		index (customerNo),
		index (receiptNo));
	