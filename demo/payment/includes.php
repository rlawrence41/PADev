<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include payment application.
$includePath .= PATH_SEPARATOR . $rootPath . "/payment/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/payment/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/payment/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/payment/ui/templates";

set_include_path($includePath);
