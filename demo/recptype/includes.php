<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include recptype application.
$includePath .= PATH_SEPARATOR . $rootPath . "/recptype/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/recptype/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/recptype/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/recptype/ui/templates";

set_include_path($includePath);
