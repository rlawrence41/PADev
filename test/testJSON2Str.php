<?php

$jsonData = json_decode('[{"contact_no":"66919","contact_id":"","company":"Jericho Road Crew","last_name":"Lawrence","first_name":"Ron","po_addr":"41 Lawrence Hts","city":"Jericho","state_abbr":"VT","zip_code":"05465","country":"","email":"ron@jerichoroadcrew.com"},{"contact_no":"68019","contact_id":"","company":"Publishers\' Assistant","last_name":"Lawrence","first_name":"Ron","po_addr":"41 Lawrence Heights","city":"Jericho","state_abbr":"VT","zip_code":"05465","country":"","email":"rlawrence@pubassist.com"}]');
echo "Datatype jsonData: " . gettype($jsonData);
$list = json2String($jsonData);


function json2String($jsonData){

	$list = "";
	foreach ($jsonData as $instance){
		$instanceStr = "";
		foreach ($instance as $field=>$value){
			if (!empty($value)){
				if ($instanceStr > "") {$instanceStr .= ", " ;}
				$instanceStr .= $value;
			}
		}
		$list .= "<li>{$instanceStr}</li>\n";
	}
	// Wrap the list items with a <ul> element.
	$list = "<ul>\n{$list}</ul>\n";
	return $list;
}



?>

<!DOCTYPE html>
<html>
<body>

<h1>Convert a JSON object to a list of strings</h1>

<p id="demo">
<?php echo $list; ?>
</p>

<p><a href="/">Back to the Test menu</a></p>

</body>
</html>
