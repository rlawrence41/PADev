<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include transStep application.
$includePath .= PATH_SEPARATOR . $rootPath . "/transStep/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/transStep/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/transStep/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/transStep/ui/templates";

set_include_path($includePath);
