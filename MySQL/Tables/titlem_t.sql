
#	TITLEM_T.SQL -- Automatically generated by MAKE_DBF.
#					Procedure generated by Ron Lawrence on 01/22/93.



	drop table titlemap;
	create table titlemap ( 
		id			integer(10) primary key auto_increment,
		titleId    	varchar(10), 
		title       varchar(33))
