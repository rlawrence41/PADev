<!DOCTYPE html>
<html>
<body>

<h2>JavaScript Objects</h2>
<p>Test to update trace fields for a record.</p>
<div hidden>
<p id="record1">{"id":"1","orderNo":"1","invoiceNo":"1","invoiceStr":"1-1","orderType":"C","orderDate":"2021-10-09 00:00:00","custPONo":"12345","supplierNo":null,"supplierIdSearch":null,"customerNo":"4","customerIdSearch":"PAYABLE, ACCOUNTS, THE DEMONSTRATION CO.","shipToNo":"3","shipToIdSearch":"DEMO, THOMAS J, ","shipToAddr":"THOMAS J DEMO26 MECHANICSBURG LANE||HINESBURG, VT 05461","lCreditCard":"1","courier":"Priority Mail","orderWeight":"4.75","shipCharge":"5.00","terms":"Prepaid","termsDesc":null,"salesRepNo":null,"salesRepIdSearch":null,"orderDiscount":"10.00","lTaxable":"1","sTaxRate":"6.000","lTaxShip":"1","cTaxRate":"5.000","adjType1":null,"adjustmnt1":"0.00","adjType2":null,"adjustmnt2":"0.00","subtotal":"58.37","total":"69.21","status":"Closed","priorState":"Closed","shipDate":"2021-10-09 00:00:00","batchNo":null,"lProcessed":"1","comment":"PAID IN FULL. THANK YOU FOR YOUR ORDER! WE GREATLY APPRECIATE YOUR BUSINESS. PLEASE BE SURE TO VISIT OUR NEW WEB SITE.","lExported":null,"updatedBy":"RWL","userNo":null,"lastUpdated":"2022-02-18 00:00:00","itemId":"3","orderKey":"1","titleNo":null,"titleId":"DEMO 1","title":"FIRST DEMONSTRATION TITLE","lInventory":"1","lConsignment":"0","itemCondtn":"","quantity":"1.00","itemDate":"2021-10-09 00:00:00","price":"24.95","discount":"0.00","deduction":"0.00","shipWeight":"1.75","itemStatus":"S","lSubscript":"0","expireDate":null,"extPrice":"24.9500","extWeight":"1.7500","itemComment":""}</p>
<p id="record2">{"id":"1", "contactId":"OWNER", "company":"PUBLISHERS' ASSISTANT", "namePrefix":"", "firstName":"RON", "midName":"", "lastName":"LAWRENCE", "nameSuffix":"", "poAddr":"41 LAWRENCE HTS.", "courAddr":"", "city":"JERICHO", "stateAbbr":"VT", "country":"", "zipCode":"05465", "countyAbbr":"", "billTo":"", "phone":"802-222-0112", "phone2":"", "email":"INFO@PUBASSIST.COM", "webUrl":"HTTPS://PUBASSIST.COM", "webservice":"", "fedIdNo":"", "san":"", "pubnetId":"", "buyerId":"", "sellerId":"", "lExclude":0, "biography":"", "portrait":"", "comment":"", "password":"", "lAuthor":0, "lCustomer":0, "lMailList":0, "lSalesRep":0, "lSupplier":0, "lWarehouse":0, "lEmployee":0, "lApproved":0, "updatedBy":"RWL", "userNo":"", "lastUpdated":"2019-12-14 00:00:00"}</p>
</div>
<p id="demo"></p>

<script>
// to represent the currently logged in user for testing.
var auth = {"id":"1","user_id":"rlawrence","contact_no":"16","contact_noSearch":"LAWRENCE, RON","email":"rlawrence41@comcast.net","phone":"802-310-8085","authCode":"2000"} ;

// Create an object:
let jsonData = document.getElementById("record2").innerHTML;
const obj = JSON.parse(jsonData);

addTraceFields(obj);

var eol = "<br/>\n" ;
var message = "Trace fields: " + eol ;
	message += "Updated By:  " + obj.updatedBy + eol;
	message += "User No:  " + obj.userNo + eol;
	message += "Last Updated:  " + obj.lastUpdated + eol;

// Display data from the object:
document.getElementById("demo").innerHTML = message;

/*
	The trace fields track who has made the latest update to the record.
 */
function addTraceFields(jsonObj){
	
	// Make sure the trace field exists in the jsonData before attempting to update.

	// The current user should be in variable "auth".
	if (jsonObj.hasOwnProperty("updatedBy")) {
		jsonObj.updatedBy = auth.user_id ;
	}
	
	if (jsonObj.hasOwnProperty("userNo")) {
		jsonObj.userNo = auth.id ;
	}
	
	if (jsonObj.hasOwnProperty("lastUpdated")) {
		jsonObj.lastUpdated = sqlNow() ;
	}
	
}

/*
	SQLNow -- returns the current date/time in SQL format.
 */
 function sqlNow(){

	const d = new Date();
	var dateStr = d.getFullYear();				//Get year as a four digit number
	var monthStr = '0' + (d.getMonth() + 1);	//Make sure month is 2 digits
	monthStr = monthStr.slice(-2);
	dateStr += "-" + monthStr;					//Get month as a number (0-11)
	dateStr += "-" + (d.getDate() + 1);			//Get day as a number (1-31)
	dateStr += " " + d.getHours();				//Get hour (0-23)
	dateStr += ":" + d.getMinutes();			//Get minute (0-59)
	dateStr += ":" + d.getSeconds();			//Get second (0-59)
	return dateStr;
 }

</script>

</body>
</html>
