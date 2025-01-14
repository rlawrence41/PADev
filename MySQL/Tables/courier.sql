
#	COURIE_T.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table courier;
	create table courier ( 
		id			integer(10) primary key auto_increment,
		courier     varchar(25), 
		lUSPS       boolean, 
		scac        varchar(20), 
		svcLevel  	varchar(2), 
		chargeType  varchar(10), 
		fixedAmt    decimal(10, 2), 
		threshold   integer(10), 
		variableAmt decimal(6, 2), 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);