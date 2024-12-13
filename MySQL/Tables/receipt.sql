
#	RECEIPT.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table receipt;
	create table receipt ( 
		id			integer(10) primary key auto_increment,
		customerNo  integer(10), 
		recptDate  	datetime, 
		orderKey   	integer(10), 
		amount      decimal(10, 2), 
		lFullPaymnt boolean, 
		recptType  	varchar(16),
		crcrdAcct  	varchar(20), 
		crcrdExpDt  datetime, 
		crcrdVCode  varchar(4), 
		crcrdAuth  	varchar(15), 
		transactId  varchar(20), 
		lItemized   boolean, 
		lRefund     boolean, 
		lProcessed  boolean, 
		lExported   boolean, 
		comment     text, 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);