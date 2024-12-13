<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include orderReceipt application.
$includePath .= PATH_SEPARATOR . $rootPath . "/orderReceipt/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderReceipt/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderReceipt/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/orderReceipt/ui/templates";

set_include_path($includePath);

