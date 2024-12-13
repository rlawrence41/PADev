<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include itemTotal application.
$includePath .= PATH_SEPARATOR . $rootPath . "/itemTotal/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemTotal/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemTotal/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemTotal/ui/templates";

set_include_path($includePath);
