use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/itemrcpt.csv'
INTO TABLE itemReceipt
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(itemNo,receiptNo,remainQty,applyQty,amount,updatedBy,@lupdate)
SET lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');

update itemReceipt ir set ir.titleId= (
select oi.titleId from orderItem oi where oi.id=ir.itemNo);

update itemReceipt ir set ir.orderKey= (
select oi.orderKey from orderItem oi where oi.id=ir.itemNo);

update itemReceipt ir set ir.customerNo= (
select o.customerNo from orders o where o.id=ir.orderKey);