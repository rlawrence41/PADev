<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the appliedOrder application.
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedOrder/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedOrder/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedOrder/rest/templates";

set_include_path($includePath);

