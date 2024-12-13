<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the itemTotal application.
$includePath .= PATH_SEPARATOR . $rootPath . "/itemTotal/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemTotal/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemTotal/rest/templates";

set_include_path($includePath);

