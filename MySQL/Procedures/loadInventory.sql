-- Load inventory transactions...

use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/inv_tran.csv'
INTO TABLE inventory
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(id,@transDate,itemNo,@titleId,itemCondtn,invStatus,location,shipmentNo,receiptNo,quantity,updatedBy,@lupdate)
SET transDate = STR_TO_DATE(@transDate, '%c/%e/%Y'),
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y'),
	titleId = @titleId,
	titleNo = (SELECT id FROM title t where t.titleId = @titleId);

