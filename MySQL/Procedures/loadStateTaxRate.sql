use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/staxrate.csv'
INTO TABLE stateTaxRate
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(stateAbbr,stateName,taxRate,@lTaxShip,updatedBy,@lupdate)
SET lTaxShip    = IF(@lTaxShip = "T", 1, 0),
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');
