<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include customerReturn application.
$includePath .= PATH_SEPARATOR . $rootPath . "/customerReturn/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerReturn/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerReturn/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerReturn/ui/templates";

set_include_path($includePath);

