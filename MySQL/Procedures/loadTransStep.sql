use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/transStep.csv'
INTO TABLE transStep
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines
( txName, stepName, txDescription, txView, resource, keyFieldName, secondaryKey, parentId, 
parentKeyField,addAction,exitAction,selectAction,summaryBandTemplate)
SET userNo = 1,
	lastUpdated = now();