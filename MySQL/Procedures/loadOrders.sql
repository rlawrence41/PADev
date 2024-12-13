use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/orders.csv'
INTO TABLE orders
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(id,orderNo,invoiceNo,orderType,@orderDate,custPONo,supplierNo,customerNo,
shipToNo,@shipToAddr,@CreditCard,courier,shipWeight,shipCharge,terms,termsDesc,
salesRepNo,discount,@Taxable,sTaxRate,@TaxShip,cTaxRate,adjType1,adjustment1,
adjType2,adjustment2,subtotal,total,status,priorState,@shipDate,batchNo,@Processed,
comment,@Exported,updatedBy,@lupdate)

SET orderDate 	= STR_TO_DATE(@orderDate, '%c/%e/%Y'),
	shipDate 	= STR_TO_DATE(@shipDate, '%c/%e/%Y'),
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y'),
	lCreditCard	= IF(@CreditCard = "T", 1, 0),
	lTaxable	= IF(@Taxable = "T", 1, 0),
	lTaxShip	= IF(@TaxShip = "T", 1, 0),
	lProcessed	= IF(@Processed = "T", 1, 0),
	lExported	= IF(@Exported = "T", 1, 0),
	shipToAddr	= REPLACE(@shipToAddr, "||", "\n");
