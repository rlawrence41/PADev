<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the contact application.
$includePath .= PATH_SEPARATOR . $rootPath . "/contact/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/contact/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/contact/rest/templates";

set_include_path($includePath);

