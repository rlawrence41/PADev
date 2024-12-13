
#	SRCOMS_T.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table srcomspc;
	create table srcomspc ( 
		id			integer(10) primary key auto_increment,
		salesrepNo  integer(10), 
		customerNo  integer(10), 
		rate        decimal(6, 2), 
		lWhenOrdrd  boolean, 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);