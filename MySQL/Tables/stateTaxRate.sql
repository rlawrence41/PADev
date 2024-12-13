drop table stateTaxRate;
create table stateTaxRate ( 
	id			integer(10) primary key auto_increment,
	stateAbbr  	varchar(2), 
	stateName  	varchar(15), 
	taxRate    	decimal(7, 3), 
	lTaxShip   	boolean, 
	updatedBy 	varchar(20), 
	userNo		integer(10),
	lastUpdated datetime);