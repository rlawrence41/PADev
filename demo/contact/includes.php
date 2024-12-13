<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include contact application.
$includePath .= PATH_SEPARATOR . $rootPath . "/contact/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/contact/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/contact/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/contact/ui/templates";

set_include_path($includePath);
