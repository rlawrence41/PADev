<?php		
	
	$fields['id'] 			= array('hidden', "record identifier", "");
	$fields['contact_no'] 	= array('text', "Contact Number", "");
	$fields['contact_id'] 	= array('text', "Contact Id", "Short name for the account");
	$fields['company'] 		= array('text', "Company", "Company or organization name");
	$fields['nameprefix'] 	= array('text', "Prefix", "e.g.: Mr., Mrs., Ms., Dr., etc.");
	$fields['first_name'] 	= array('text', "First Name", "");
	$fields['mid_name'] 	= array('text', "Middle Name", "middle name or initial");
	$fields['last_name'] 	= array('text', "Last Name", "");
	$eol = "<br/>\n";

	foreach ($fields as $fieldName => $fieldArray) {
		
		echo "Field:  {$fieldName}\n";
 		echo "\tType: " . $fieldArray[0] .$eol;
		echo "\tLabel: " . $fieldArray[1] .$eol;
		echo "\tDescription: " . $fieldArray[2] .$eol;
#		var_dump($fieldArray);
	}
	