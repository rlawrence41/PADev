
#	LEDGER.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table ledger;
	create table ledger ( 
		id			integer(10) primary key auto_increment,
		transDate  	datetime, 
		transEvent  varchar(10), 
		transType  	varchar(20), 
		accountNo   integer(10), 
		acctType   	char(1), 
		amount      decimal(10, 2), 
		docNo       varchar(20), 
		orderKey   	integer(10), 
		itemNo      integer(10), 
		receiptNo   integer(10), 
		specNo      integer(10), 
		lExported   boolean, 
		comment     text, 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime,
		index(transEvent),
		index (transType),
		index (accountNo),
		index (orderKey),
		index (itemNo),
		index (receiptNo),
		index(specNo)
);