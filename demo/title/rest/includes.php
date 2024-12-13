<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the title application.
$includePath .= PATH_SEPARATOR . $rootPath . "/title/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/title/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/title/rest/templates";

set_include_path($includePath);

