<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include itemReceipt application.
$includePath .= PATH_SEPARATOR . $rootPath . "/itemReceipt/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemReceipt/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemReceipt/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/itemReceipt/ui/templates";

set_include_path($includePath);

