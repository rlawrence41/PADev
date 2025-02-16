
#	ONIXCO_T.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table onixcodes;
	create table onixcodes ( 
		id			integer(10) primary key auto_increment,
		listNo      integer(3), 
		sortOrder   integer(3), 
		value       varchar(5), 
		descript    varchar(70), 
		comment     text, 
		issue       integer(4), 
		paFieldNam  varchar(10),
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);
