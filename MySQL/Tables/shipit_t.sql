
#	SHIPIT_T.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table shipitem;
	create table shipitem ( 
		id			integer(10) primary key auto_increment,
		itemNo      integer(10), 
		itemQty    	decimal(10, 2), 
		cartonNo    integer(10), 
		comment     text, 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);