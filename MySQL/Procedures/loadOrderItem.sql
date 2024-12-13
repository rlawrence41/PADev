use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/ordritem.csv'
INTO TABLE orderItem
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(id,orderKey,titleId,title,@Inventory,@Consignment,itemCondtn,quantity,@orderDate,
price,discount,deduction,shipWeight,itemStatus,@Subscript,@expireDate,comment,
updatedBy,@lupdate)
SET orderDate 	= STR_TO_DATE(@orderDate, '%c/%e/%Y'),
	expireDate 	= STR_TO_DATE(@expireDate, '%c/%e/%Y'),
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y'),
	lInventory	= IF(@Inventory = "T", 1, 0), 
	lConsignment= IF(@Consignment = "T", 1, 0),
	lSubscript	= IF(@Subscript = "T", 1, 0);
	