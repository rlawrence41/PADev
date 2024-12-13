<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the stateTaxRate application.
$includePath .= PATH_SEPARATOR . $rootPath . "/stateTaxRate/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/stateTaxRate/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/stateTaxRate/rest/templates";

set_include_path($includePath);

