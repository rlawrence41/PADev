<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the appliedItem application.
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedItem/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedItem/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedItem/rest/templates";

set_include_path($includePath);

