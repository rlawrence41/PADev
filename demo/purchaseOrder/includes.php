<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include purchaseOrder application.
$includePath .= PATH_SEPARATOR . $rootPath . "/purchaseOrder/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/purchaseOrder/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/purchaseOrder/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/purchaseOrder/ui/templates";

set_include_path($includePath);

