
--	transStep.SQL -- Creates a database table.

	drop table transStep;
	create table transStep ( 
		id   		integer(10) primary key auto_increment, 
		txName		varChar(30),
		stepName	varChar(50),
		txDescription text,
		txView  	varchar(20),
		resource	varchar(30),
		keyFieldName varchar(30),
		secondaryKey varchar(30),
		parentId	integer(10),
		parentKeyField varchar(30),
		addAction	varChar(50),
		exitAction	varChar(50),
		selectAction varChar(50),
		summaryBandTemplate	varChar(100),
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);
		
		
