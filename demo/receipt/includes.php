<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include receipt application.
$includePath .= PATH_SEPARATOR . $rootPath . "/receipt/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/receipt/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/receipt/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/receipt/ui/templates";

set_include_path($includePath);
