drop table localTaxRate;
create table localTaxRate ( 
	id			integer(10) primary key auto_increment,
	stateAbbr  	varchar(2), 
	munAbbr  	varchar(4), 
	municipality varchar(30), 
	taxRate    	decimal(7, 3), 
	updatedBy 	varchar(20), 
	userNo		integer(10),
	lastUpdated datetime);