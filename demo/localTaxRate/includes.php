<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include localTaxRate application.
$includePath .= PATH_SEPARATOR . $rootPath . "/localTaxRate/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/localTaxRate/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/localTaxRate/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/localTaxRate/ui/templates";

set_include_path($includePath);
