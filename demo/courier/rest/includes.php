<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the courier application.
$includePath .= PATH_SEPARATOR . $rootPath . "/courier/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/courier/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/courier/rest/templates";

set_include_path($includePath);

