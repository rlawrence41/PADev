use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/receipt.csv'
INTO TABLE receipt
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(id,customerNo,@recptDate,orderKey,amount,@FullPaymnt,recptType,crcrdAcct,@crcrdExpDt,
crcrdVCode,crcrdAuth,transactId,@Itemized,@Refund,@Processed,@Exported,comment,
updatedBy,@lupdate)
SET recptDate	= STR_TO_DATE(@recptDate, '%c/%e/%Y'), 
	lFullPaymnt	= IF(@FullPaymnt = "T", 1, 0), 
	crcrdExpDt	= STR_TO_DATE(@crcrdExpDt, '%c/%e/%Y'), 
	lItemized	= IF(@Itemized = "T", 1, 0),
	lRefund		= IF(@Refund = "T", 1, 0),
	lProcessed	= IF(@Processed = "T", 1, 0),
	lExported	= IF(@Exported = "T", 1, 0),
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');
