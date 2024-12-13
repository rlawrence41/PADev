use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/ordercpt.csv'
INTO TABLE orderReceipt
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(orderKey,receiptNo,customerNo,orderTotal,orderPaid,surcharges,srchgPaid,unapplied,updatedBy,@lupdate)
SET lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');
	