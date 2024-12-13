<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include <<resource>> application.
$includePath .= PATH_SEPARATOR . $rootPath . "/<<resource>>/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/<<resource>>/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/<<resource>>/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/<<resource>>/ui/templates";

set_include_path($includePath);
