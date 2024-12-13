<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the transStep application.
$includePath .= PATH_SEPARATOR . $rootPath . "/transStep/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/transStep/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/transStep/rest/templates";

set_include_path($includePath);

