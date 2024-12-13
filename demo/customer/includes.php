<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include customer application.
$includePath .= PATH_SEPARATOR . $rootPath . "/customer/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/customer/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/customer/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/customer/ui/templates";

set_include_path($includePath);
