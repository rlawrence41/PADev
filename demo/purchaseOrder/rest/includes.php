<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the purchaseOrder application.
$includePath .= PATH_SEPARATOR . $rootPath . "/purchaseOrder/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/purchaseOrder/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/purchaseOrder/rest/templates";

set_include_path($includePath);

