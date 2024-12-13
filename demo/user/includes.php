<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include user application.
$includePath .= PATH_SEPARATOR . $rootPath . "/user/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/user/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/user/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/user/ui/templates";

set_include_path($includePath);
