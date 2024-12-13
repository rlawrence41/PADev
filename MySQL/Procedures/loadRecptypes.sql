use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/recptype.csv'
INTO TABLE recptype
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(recptType,@CreditCard,updatedBy,@lupdate)
SET lCreditCard	= IF(@CreditCard = "T", 1, 0), 
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');
