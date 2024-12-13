<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the keyField application.
$includePath .= PATH_SEPARATOR . $rootPath . "/keyField/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/keyField/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/keyField/rest/templates";

set_include_path($includePath);

