<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the recptype application.
$includePath .= PATH_SEPARATOR . $rootPath . "/recptype/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/recptype/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/recptype/rest/templates";

set_include_path($includePath);

