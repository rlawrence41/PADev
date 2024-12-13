use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/ldgrtran.csv'
INTO TABLE ledger
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(id,@transDate,transEvent,transType,accountNo,acctType,amount,docNo,orderKey,itemNo,
receiptNo,specNo,@Exported,comment,updatedBy,@lupdate)
SET transDate 	= STR_TO_DATE(@transDate, '%c/%e/%Y'),
	lExported	= IF(@Exported = "T", 1, 0),
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');

--The transType field has been expanded.
update ledger set transType="Amount Received" where transType = "Amount Rcvd";