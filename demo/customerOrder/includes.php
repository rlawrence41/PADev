<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include customerOrder application.
$includePath .= PATH_SEPARATOR . $rootPath . "/customerOrder/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerOrder/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerOrder/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/customerOrder/ui/templates";

set_include_path($includePath);

