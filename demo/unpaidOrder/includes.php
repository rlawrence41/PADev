<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include unpaidOrder application.
$includePath .= PATH_SEPARATOR . $rootPath . "/unpaidOrder/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/unpaidOrder/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/unpaidOrder/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/unpaidOrder/ui/templates";

set_include_path($includePath);
