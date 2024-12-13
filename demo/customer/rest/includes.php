<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the customer application.
$includePath .= PATH_SEPARATOR . $rootPath . "/customer/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/customer/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/customer/rest/templates";

set_include_path($includePath);

