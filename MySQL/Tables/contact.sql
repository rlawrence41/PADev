
--	CONTACT.SQL -- Creates a database table.

	drop table contact;
	create table contact ( 
		id   		integer(10) primary key auto_increment, 
		contactId  	varchar(20), 
		company     varchar(33), 
		namePrefix  varchar(10), 
		firstName  	varchar(15), 
		midName    	varchar(10), 
		lastName   	varchar(25), 
		nameSuffix  varchar(20), 
		poAddr     	text, 
		courAddr   	text, 
		city        varchar(30), 
		stateAbbr  	varchar(2), 
		country     varchar(20), 
		zipCode    	varchar(10), 
		countyAbbr  varchar(4), 
		billTo		integer(10), 
		phone       varchar(16), 
		phone2      varchar(20), 
		email       text, 
		webUrl     	text, 
		webservice  text, 
		fedIdNo     varchar(11), 
		san         varchar(15), 
		pubnetId    varchar(15), 
		buyerId    	varchar(20), 
		sellerId   	varchar(20), 
		lExclude    boolean, 
		biography   text, 
		portrait    text, 
		comment     text, 
		password    varchar(20), 
		lAuthor     boolean, 
		lCustomer   boolean, 
		lMailList   boolean, 
		lSalesRep   boolean, 
		lSupplier   boolean, 
		lWarehouse  boolean, 
		lEmployee   boolean, 
		lApproved   boolean, 
		updatedBy 	varchar(20), 
		userNo		integer(10),
		lastUpdated datetime);