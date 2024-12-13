<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the orderReceipt application.
$includePath .= PATH_SEPARATOR . $rootPath . "/orderReceipt/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderReceipt/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderReceipt/rest/templates";

set_include_path($includePath);

