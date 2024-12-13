<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include appliedItem application.
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedItem/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedItem/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedItem/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/appliedItem/ui/templates";

set_include_path($includePath);

