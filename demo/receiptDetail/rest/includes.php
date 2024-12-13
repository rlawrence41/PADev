<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the receiptDetail application.
$includePath .= PATH_SEPARATOR . $rootPath . "/receiptDetail/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/receiptDetail/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/receiptDetail/rest/templates";

set_include_path($includePath);

