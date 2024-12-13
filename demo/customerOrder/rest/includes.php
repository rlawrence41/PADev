<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the customerOrder application.
$includePath .= PATH_SEPARATOR . $rootPath . "/customerOrder/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerOrder/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerOrder/rest/templates";

set_include_path($includePath);

