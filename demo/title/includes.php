<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include title application.
$includePath .= PATH_SEPARATOR . $rootPath . "/title/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/title/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/title/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/title/ui/templates";

set_include_path($includePath);
