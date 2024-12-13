--	customer.SQL -- Creates a database table.

	drop table customer;
	create table customer ( 
		id			integer(10) primary key auto_increment,
		customerNo	integer(10),
		crcrdVndr  	varchar(16), 
		crcrdAcct  	varchar(20), 
		crcrdexpdt  datetime, 
		salesRepNo  integer(10), 
		taxExempt  	boolean, 
		discount    decimal(6, 2), 
		terms       varchar(20), 
		termsDesc  	varchar(20), 
		credLimit  	decimal(10, 2), 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);