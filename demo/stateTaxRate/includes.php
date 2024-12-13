<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include stateTaxRate application.
$includePath .= PATH_SEPARATOR . $rootPath . "/stateTaxRate/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/stateTaxRate/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/stateTaxRate/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/stateTaxRate/ui/templates";

set_include_path($includePath);
