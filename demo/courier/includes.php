<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include courier application.
$includePath .= PATH_SEPARATOR . $rootPath . "/courier/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/courier/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/courier/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/courier/ui/templates";

set_include_path($includePath);
