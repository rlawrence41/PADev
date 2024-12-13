<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the titleLiabilitySp application.
$includePath .= PATH_SEPARATOR . $rootPath . "/titleLiabilitySp/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/titleLiabilitySp/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/titleLiabilitySp/rest/templates";

set_include_path($includePath);

