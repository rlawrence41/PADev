<?php 

// Include the standard REST API folders.
include("../../common/rest/includes.php");
$includePath = get_include_path() ;

// Add the <<resource>> application.
$includePath .= PATH_SEPARATOR . $rootPath . "/<<resource>>/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/<<resource>>/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/<<resource>>/rest/templates";

set_include_path($includePath);

