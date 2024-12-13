<?php
$eol = "<br/>\n";
$json = '[{"contact_no":"66919","contact_id":"","company":"Jericho Road Crew","last_name":"Lawrence","first_name":"Ron","city":"Jericho","state_abbr":"VT","zip_code":"05465"},{"contact_no":"67678","contact_id":"","company":"","last_name":"Lawrence","first_name":"Richard","city":"Berlin","state_abbr":"","zip_code":""},{"contact_no":"68019","contact_id":"","company":"Publishers\' Assistant","last_name":"Lawrence","first_name":"Ron","city":"Jericho","state_abbr":"VT","zip_code":"05465"}]';
$json = '{"specNo":"32", "sequenceNo":"", "transType":"Royalty Fee", "title_no":"30", "payeeId":"38135", "threshold":"0", "startDate":"NULL", "endDate":"NULL", "discount":"0.00", "rate":"33.00", "net":0, "whenOrdered":0}';

#var_dump(json_decode($json));
#var_dump(json_decode($json, true));

$result = json_decode($json, true);

// If so, does it look like JSON?
if (!is_array($input)) {
$message = "JSON Decode Error: " . json_last_error_msg();
	echo $message . $eol;
}

var_dump($result);
echo $eol;
foreach ($result as $key => $record) {
	echo $eol . $eol;
	echo "Record: " . $key . $eol;
	foreach ($record as $fieldName => $fieldValue) {
		echo "\t" . $fieldName . " = " . $fieldValue . $eol;
	}
}
?>