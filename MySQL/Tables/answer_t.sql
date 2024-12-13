#	ANSWER_T.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			11/6/2014 using MAKE_DBF.

	drop table answer;
	create table answer ( 
		id   		integer(10) primary key auto_increment, 
		intrviewNo  integer(10), 
		questionNo  integer(10), 
		code        varchar(4), 
		comment     text, 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);