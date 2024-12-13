-- Load data from royalty specifications...

use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/ryltyspc.csv'
INTO TABLE titleLiabilitySp
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(id,@titleId,accountNo,threshold,discount,rate,@net,@whenShipped,updatedBy,@lupdate)
SET transType = "Royalty Fee",
	whenShipped	= IF(@whenShipped = "T", 1, 0),
	net = IF(@net = "T", 1, 0),
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y'),
	titleNo = (SELECT id FROM title t where t.titleId = @titleId);


-- Now capture the consignment costs from the titleCost specifications...
-- Tag the non-consignment spec's in the userNo field temporarily.

LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/titlcost.csv'
INTO TABLE titleLiabilitySp
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(@spec_no,@titleId,@orderQty,@cost,discount,@net,rate,@whenShipped,updatedBy,@lupdate)
SET transType = "Consignment Cost",
	whenShipped	= IF(@whenShipped = "T", 1, 0),
	net = IF(@net = "T", 1, 0),
	userNo = if(@cost > 0.00, -1, 0),
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y'),
	titleNo = (SELECT t.id FROM title t where t.titleId = @titleId),
	accountNo = (SELECT t.supplierNo FROM title t where t.titleId = @titleId);

-- Delete non-consignment spec's.
DELETE FROM titleLiabilitySp WHERE userNo < 0;

