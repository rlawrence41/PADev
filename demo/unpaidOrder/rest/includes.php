<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the unpaidOrder application.
$includePath .= PATH_SEPARATOR . $rootPath . "/unpaidOrder/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/unpaidOrder/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/unpaidOrder/rest/templates";

set_include_path($includePath);

