use pubassistDemo;
LOAD DATA LOCAL INFILE '/var/www/MySQL/Data/SAMPLEDATA/title.csv'
INTO TABLE title
fields terminated by "," 
optionally enclosed by '"' 
lines terminated by "\n"
ignore 1 lines

(id,titleId,title,titleNoPre,subtitle,isbn,isbn13,ean13,upc,lccn,classId,supplierNo,
imprintNo,vendorNo,vendorRole,@Subscript,@Inventory,@Consignmnt,retPrice,cartonCount,
cartonWgt,salesCount,bookLbs,bookOz,weight,weightUnit,pageCount,height,width,
thickness,linearUnit,@BarCode,barcodetyp,shelfId,saftyStock,reordrQty,leadTime,
@OutOfPrint,copyRtYear,edition,editionNo,pubStatus,@pubDate,discCode,genre,audience,
audRangTyp,audRange1,audRange2,language,formCode,formDetail,eFormURL,available,
@availDate,returnCode,collection,parentId,nameInSet,volumeNo,promoText,boComment,
TOC,sampleText,imageFile,imageFile2,reviews,comment,@Approved,updatedBy,@lupdate)
SET lSubscript	= IF(@Subscript = "T", 1, 0),
	lInventory	= IF(@Inventory = "T", 1, 0), 
	lConsignment= IF(@Consignment = "T", 1, 0),
	lBarCode	= IF(@BarCode = "T", 1, 0),
	lOutOfPrint	= IF(@OutOfPrint = "T", 1, 0),
	pubDate		= STR_TO_DATE(@pubDate, '%c/%e/%Y'), 
	availDate	= STR_TO_DATE(@availDate, '%c/%e/%Y'), 
	lApproved	= IF(@Approved = "T", 1, 0),
	lastUpdated = STR_TO_DATE(@lupdate, '%c/%e/%Y');
