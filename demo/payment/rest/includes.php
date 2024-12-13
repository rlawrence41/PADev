<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the payment application.
$includePath .= PATH_SEPARATOR . $rootPath . "/payment/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/payment/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/payment/rest/templates";

set_include_path($includePath);

