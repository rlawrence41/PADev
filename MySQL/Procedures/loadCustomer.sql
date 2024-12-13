use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/customer.csv'
INTO TABLE customer
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines
(id,crcrdVndr,crcrdAcct,@crcrdExpDt,salesRepNo,@taxExempt,discount,
terms,termsDesc,credLimit,updatedBy,@lupdate)
SET taxExempt = if(@taxExempt = "T", true, false),
	crcrdExpDt = STR_TO_DATE(@crcrdExpDt, '%c/%e/%Y'),
	userNo = 1,
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');