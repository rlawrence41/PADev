<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the itemReceipt application.
$includePath .= PATH_SEPARATOR . $rootPath . "/itemReceipt/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemReceipt/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemReceipt/rest/templates";

set_include_path($includePath);

