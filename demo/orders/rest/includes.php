<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the orders application.
$includePath .= PATH_SEPARATOR . $rootPath . "/orders/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/orders/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/orders/rest/templates";

set_include_path($includePath);

