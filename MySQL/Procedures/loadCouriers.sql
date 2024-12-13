use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/courier.csv'
INTO TABLE courier
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(courier,@USPS,scac,svcLevel,chargeType,fixedAmt,threshold,variableAmt,updatedBy,@lupdate)
SET lUSPS   = IF(@USPS = "T", 1, 0), 
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');
