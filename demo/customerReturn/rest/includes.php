<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the customerReturn application.
$includePath .= PATH_SEPARATOR . $rootPath . "/customerReturn/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerReturn/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerReturn/rest/templates";

set_include_path($includePath);

