<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include titleLiabilitySp application.
$includePath .= PATH_SEPARATOR . $rootPath . "/titleLiabilitySp/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/titleLiabilitySp/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/titleLiabilitySp/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/titleLiabilitySp/ui/templates";

set_include_path($includePath);
