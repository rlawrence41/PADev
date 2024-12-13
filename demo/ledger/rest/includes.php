<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the ledger application.
$includePath .= PATH_SEPARATOR . $rootPath . "/ledger/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/ledger/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/ledger/rest/templates";

set_include_path($includePath);

