
--	inventoryTran.sql -- Creates a database table for inventory transactions.

	drop table inventory;
	create table inventory ( 
		id			integer(10) primary key auto_increment,
		transDate  	datetime, 
		itemNo      integer(10),
		titleNo		integer(10),
		titleId    	varchar(20), 
		itemCondtn  char(1) DEFAULT "", 
		invState	char(1), 
		location    integer(10) DEFAULT 0, 
		shipmentNo  integer(10), 
		receiptNo   integer(10), 
		itemReceiptNo	integer(10),
		quantity    decimal(10, 2), 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime)
		KEY `invState` (`invState`),
		KEY `location` (`location`),
		KEY `itemCondtn` (`itemCondtn`),
		KEY `titleNo` (`titleNo`);