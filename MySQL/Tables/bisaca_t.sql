
#	BISACA_T.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	DROP TABLE bisacackcode;
	CREATE TABLE bisacackcode ( 
		id   		integer(10) primary key auto_increment,
		ackcode     varchar(2), 
		descript    varchar(70),
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);