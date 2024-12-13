<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include keyField application.
$includePath .= PATH_SEPARATOR . $rootPath . "/keyField/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/keyField/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/keyField/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/keyField/ui/templates";

set_include_path($includePath);
