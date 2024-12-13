<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include inventory application.
$includePath .= PATH_SEPARATOR . $rootPath . "/inventory/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/inventory/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/inventory/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/inventory/ui/templates";

set_include_path($includePath);
