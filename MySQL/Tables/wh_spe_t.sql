
#	WH_SPE_T.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table wh_spec;
	create table wh_spec ( 
		id			integer(10) primary key auto_increment,
		accountNo   integer(10), 
		titleId    	varchar(20), 
		freeQty     integer(10), 
		expiredate  datetime, 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);