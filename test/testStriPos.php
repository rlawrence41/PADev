<?php
include("../common/common.php");
$eol = "\n<br/>";
$sql = "update user set user.user_id = 'henschelhaus',user.email = 'kira@henschelhaus.com',user.authCode = 1000" ;
$pos1 = stripos($sql, "update");
$pos2 = stripos($sql, "where");
echo $sql . $eol;
echo "Update positon: " ;
echo $pos1 ;
echo $eol;
echo "Update?  " . (($pos1 === false) ? "No" : "Yes") . $eol;
echo "Where?  " . (($pos2 === false) ? "No" : "Yes") . $eol;
echo "Where Clause missing? " . (missingWhere($sql)? "Yes" : "No") . $eol;
echo $eol ;
echo $eol ;


$sql = "update user set user.user_id = 'henschelhaus',user.email = 'kira@henschelhaus.com',user.authCode = 1000 where user.user_no=19" ;
echo $sql . $eol;
echo "Where Clause missing? " . (missingWhere($sql)? "Yes" : "No") . $eol;
echo $eol ;
echo $eol ;


$sql = "select contact_no, contact_id, company, last_name, first_name, po_addr, city, state_abbr, zip_code, country, email, phone, phone2, web_url, webservice from contact %limit%" ;
$pos1 = stripos($sql, "update");
$pos2 = stripos($sql, "where");
echo $sql . $eol;
echo "Update positon: " ;
echo $pos1 ;
echo $eol;
echo "Where Clause missing? " . (missingWhere($sql)? "Yes" : "No") . $eol;

$findme    = 'a';
$mystring1 = 'xyz';
$mystring2 = 'ABC';

$pos1 = stripos($mystring1, $findme);
$pos2 = stripos($mystring2, $findme);

// Nope, 'a' is certainly not in 'xyz'
if ($pos1 === false) {
    echo "The string '$findme' was not found in the string '$mystring1'";
}

// Note our use of ===.  Simply == would not work as expected
// because the position of 'a' is the 0th (first) character.
if ($pos2 !== false) {
    echo "We found '$findme' in '$mystring2' at position $pos2";
}