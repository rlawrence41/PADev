<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the receipt application.
$includePath .= PATH_SEPARATOR . $rootPath . "/receipt/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/receipt/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/receipt/rest/templates";

set_include_path($includePath);

