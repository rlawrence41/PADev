use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/code.csv'
INTO TABLE code
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(code,codeType,descript,enteredby,@lupdate)
SET lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');
