
#	CODE.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table code;
	create table code ( 
		id 			integer(10) primary key auto_increment,
		code        char(4) unique,
		codeType	varchar(10),
		descript    varchar(70), 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);