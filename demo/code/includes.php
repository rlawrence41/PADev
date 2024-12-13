<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include code application.
$includePath .= PATH_SEPARATOR . $rootPath . "/code/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/code/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/code/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/code/ui/templates";

set_include_path($includePath);
