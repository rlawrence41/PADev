<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the user application.
$includePath .= PATH_SEPARATOR . $rootPath . "/user/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/user/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/user/rest/templates";

set_include_path($includePath);

