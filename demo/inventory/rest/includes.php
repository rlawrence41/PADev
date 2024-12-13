<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the inventory application.
$includePath .= PATH_SEPARATOR . $rootPath . "/inventory/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/inventory/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/inventory/rest/templates";

set_include_path($includePath);

