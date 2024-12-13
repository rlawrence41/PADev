	drop table keyField;
	create table keyField ( 
		id			integer(10) primary key auto_increment,
		resource    varchar(32), 
		keyField   	varchar(30), 
		alias       varchar(30) unique, 
		lastKey    	integer(10) NOT NULL DEFAULT 0);