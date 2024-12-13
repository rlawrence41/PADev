<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include orderItem application.
$includePath .= PATH_SEPARATOR . $rootPath . "/orderItem/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderItem/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderItem/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderItem/ui/templates";

set_include_path($includePath);
