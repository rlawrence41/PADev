<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the localTaxRate application.
$includePath .= PATH_SEPARATOR . $rootPath . "/localTaxRate/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/localTaxRate/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/localTaxRate/rest/templates";

set_include_path($includePath);

