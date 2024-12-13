<!DOCTYPE html>
<html>
<body>

<h2>JavaScript Objects</h2>
<p>Test to see if a key exists in the json object.</p>
<div hidden>
<p id="record">{"id":"1","orderNo":"1","invoiceNo":"1","invoiceStr":"1-1","orderType":"C","orderDate":"2021-10-09 00:00:00","custPONo":"12345","supplierNo":null,"supplierIdSearch":null,"customerNo":"4","customerIdSearch":"PAYABLE, ACCOUNTS, THE DEMONSTRATION CO.","shipToNo":"3","shipToIdSearch":"DEMO, THOMAS J, ","shipToAddr":"THOMAS J DEMO26 MECHANICSBURG LANE||HINESBURG, VT 05461","lCreditCard":"1","courier":"Priority Mail","orderWeight":"4.75","shipCharge":"5.00","terms":"Prepaid","termsDesc":null,"salesRepNo":null,"salesRepIdSearch":null,"orderDiscount":"10.00","lTaxable":"1","sTaxRate":"6.000","lTaxShip":"1","cTaxRate":"5.000","adjType1":null,"adjustmnt1":"0.00","adjType2":null,"adjustmnt2":"0.00","subtotal":"58.37","total":"69.21","status":"Closed","priorState":"Closed","shipDate":"2021-10-09 00:00:00","batchNo":null,"lProcessed":"1","comment":"PAID IN FULL. THANK YOU FOR YOUR ORDER! WE GREATLY APPRECIATE YOUR BUSINESS. PLEASE BE SURE TO VISIT OUR NEW WEB SITE.","lExported":null,"updatedBy":"RWL","userNo":null,"lastUpdated":"2022-02-18 00:00:00","itemId":"3","orderKey":"1","titleNo":null,"titleId":"DEMO 1","title":"FIRST DEMONSTRATION TITLE","lInventory":"1","lConsignment":"0","itemCondtn":"","quantity":"1.00","itemDate":"2021-10-09 00:00:00","price":"24.95","discount":"0.00","deduction":"0.00","shipWeight":"1.75","itemStatus":"S","lSubscript":"0","expireDate":null,"extPrice":"24.9500","extWeight":"1.7500","itemComment":""}</p>
</div>
<p id="demo"></p>

<script>
// Create an object:
let jsonData = document.getElementById("record").innerHTML;
const obj = JSON.parse(jsonData);
var message2 = obj.shipToIdSearch;
document.getElementById("demo").innerHTML = message2;

var key = "lastUpdated";
var message = "Does the key, " + key + ", exist?  ";
if (obj.hasOwnProperty(key)) message += "Yes!  " + obj[key]; 
else message += "nope. :-/";

// Display data from the object:
document.getElementById("demo").innerHTML = message;
</script>

</body>
</html>
