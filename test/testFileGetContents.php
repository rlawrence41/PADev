<?php

	$fieldName = "MyField";
	$fieldArray = array("text", $fieldName, "Enter something useful here.");
	$fieldType 	= $fieldArray[0];
	$fieldLabel = (strlen($fieldArray[1])==0)? $fieldName: $fieldArray[1];
	$fieldDescription = (strlen($fieldArray[2])==0)? $fieldLabel: $fieldArray[2];
	$templatePath = "inputText.html";
	$eol = "<br/>\n";
	
	$includePath = '../templates';
	set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);
	
	
	echo "Include Path:  " . get_include_path() . $eol;
	echo "FILE_USE_INCLUDE_PATH:  ";
	echo FILE_USE_INCLUDE_PATH;
	echo $eol;

	// Capture the template HTML for the element.
	$html = file_get_contents($templatePath, FILE_USE_INCLUDE_PATH);
	
	// Insert the field name as the id for the element.
	$html = str_replace("{fieldName}", $fieldName, $html);
	
	// Insert the field label into the element.
	$html = str_replace("{fieldLabel}", $fieldLabel, $html);

	// Finally, the field description is generally used as a placeholder.
	$html = str_replace("{fieldDescription}", $fieldDescription, $html);

	echo $html;