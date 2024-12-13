<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include receiptDetail application.
$includePath .= PATH_SEPARATOR . $rootPath . "/receiptDetail/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/receiptDetail/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/receiptDetail/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/receiptDetail/ui/templates";

set_include_path($includePath);
