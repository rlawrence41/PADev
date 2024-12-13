<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include orders application.
$includePath .= PATH_SEPARATOR . $rootPath . "/orders/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/orders/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/orders/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/orders/ui/templates";

set_include_path($includePath);
