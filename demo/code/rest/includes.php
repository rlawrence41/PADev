<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the code application.
$includePath .= PATH_SEPARATOR . $rootPath . "/code/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/code/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/code/rest/templates";

set_include_path($includePath);

