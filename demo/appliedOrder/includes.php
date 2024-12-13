<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include appliedOrder application.
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedOrder/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedOrder/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedOrder/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedOrder/ui/templates";

set_include_path($includePath);

