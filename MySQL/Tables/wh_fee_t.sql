
#	WH_FEE_T.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table wh_fees;
	create table wh_fees ( 
		id			integer(10) primary key auto_increment,
		freeQty     integer(10), 
		fee         decimal(10, 2), 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);