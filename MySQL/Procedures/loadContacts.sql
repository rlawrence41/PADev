use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/contact.csv'
INTO TABLE contact
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines
(id,contactId,company,namePrefix,firstName,midName,lastName,nameSuffix,poAddr,
courAddr,city,stateAbbr,country,zipCode,countyAbbr,billTo,phone,phone2,email,
webUrl,webService,fedIdNo,san,pubnetid,buyerId,sellerId,@Exclude,biography,portrait,
comment,@Author,password,@Customer,@MailList,@SalesRep,@Supplier,@Warehouse,
@Employee,@Approved,updatedBy,@lupdate)
SET lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y'),
	lExclude    = IF(@Exclude = "T", 1, 0), 
	lAuthor     = IF(@Author = "T", 1, 0), 
	lCustomer   = IF(@Customer = "T", 1, 0), 
	lMailList   = IF(@MailList = "T", 1, 0), 
	lSalesRep   = IF(@salesRep = "T", 1, 0), 
	lSupplier   = IF(@Supplier = "T", 1, 0), 
	lWarehouse  = IF(@Warehouse = "T", 1, 0), 
	lEmployee   = IF(@Employee = "T", 1, 0), 
	lApproved   = IF(@Approved = "T", 1, 0);