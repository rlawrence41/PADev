<?php

// Set up the standard application.
include("../includes.php");
$includePath = get_include_path() ;

// Include ledger application.
$includePath .= PATH_SEPARATOR . $rootPath . "/ledger/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/ledger/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/ledger/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/ledger/ui/templates";

set_include_path($includePath);
