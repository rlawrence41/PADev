<?php

// This is the includes file for the REST API.  This procedure will build the 
// include path for all modules needed by REST.  It then includes the same  
// common procedures and configuration used in the application layer.

// Note:  $_SERVER['DOCUMENT_ROOT'] will return the root directory of this 
// web site.  The REST API will generally have its own domain which should be
// specified in the configuration.  Thus, the use of $_SERVER['DOCUMENT_ROOT']
// should work here.

// This includes procedure is expected to be in the /common/rest directory of 
// the PubAssist application.  

$rootPath = $_SERVER['DOCUMENT_ROOT'];

$includePath = get_include_path() ;
$includePath .= PATH_SEPARATOR . $rootPath;

// Note:  $_SERVER['DOCUMENT_ROOT'] contains the trailing slash in the path.
$includePath .= PATH_SEPARATOR . $rootPath . "/common";

// Include REST API folders
$includePath .= PATH_SEPARATOR . $rootPath . "/common/rest";
$includePath .= PATH_SEPARATOR . $rootPath . "/common/rest/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/common/rest/templates";

// Make the UI classes available as well.
$includePath .= PATH_SEPARATOR . $rootPath . "/common/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/common/ui/templates";

set_include_path($includePath);

// Include the common procedures.
include_once("common.php");

// Set the site-specific configuration for the application.
$config = configFile();
include_once($config);

// Include REST components
include_once ("request.class.php");
include_once ("webObj.class.php");

