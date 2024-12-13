<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the orderItem application.
$includePath .= PATH_SEPARATOR . $rootPath . "/orderItem/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderItem/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderItem/rest/templates";

set_include_path($includePath);

