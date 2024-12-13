<?php

// This is the includes file for the TEST folder (as opposed to the application or
// REST API).  This procedure will build the include path for all modules needed
// in the application.  It then includes the common procedures and configuration
// for the installation.

// Note:  $_SERVER['DOCUMENT_ROOT'] will return the root directory of the 
// web site.  If this application is ported into a web site (e.g. a Wordpress
// site), the document root will not be the root folder of this application.

// This includes procedure is expected to be in the root directory of the 
// PubAssist application.  The __DIR__ constant should return that path. 

// Note that the __DIR__ constant does NOT include the trailing slash
// for the folder.  So, it should be pre-pended to subfolders.

#$rootPath = $_SERVER['DOCUMENT_ROOT'];  // Don't use.  Could change if ported to another web site.
#$rootPath = __DIR__ ;	// The directory of this includes procedure.

$rootPath = "/var/www/test/demo";		// ...for this test procedure.
$commonPath = "/var/www/test/common";

// Add the root to the existing include path.
$includePath = get_include_path() ;

// Include the demo and common folders...
$includePath .= PATH_SEPARATOR . $rootPath ;
$includePath .= PATH_SEPARATOR . $commonPath ;

// Include User Interface folders
$includePath .= PATH_SEPARATOR . $commonPath . "/ui/classes";
$includePath .= PATH_SEPARATOR . $commonPath . "/ui/css";
$includePath .= PATH_SEPARATOR . $commonPath . "/ui/js";
$includePath .= PATH_SEPARATOR . $commonPath . "/ui/templates";


set_include_path($includePath);

// Include the common procedures.
include_once("common.php");

// Set the site-specific configuration for the application.
$config = configFile();
include_once($config);

// Include the UI components.
include_once ("webObj.class.php");
include_once ("page.class.php");
include_once ("page.report.class.php");
include_once ("widget.class.php");
include_once ("appPage.class.php");
include_once ("menu.class.php");
include_once ("standardMenu.class.php");
include_once ("context.class.php");
include_once ("tableControl.class.php");
include_once ("pageNav.class.php");
include_once ("table.class.php");
include_once ("form.class.php");
include_once ("report.class.php");
include_once ("searchField.class.php");
include_once ("searchList.class.php");
include_once ("auth.class.php");
include_once ("progressBar.class.php");
include_once ("messageBox.class.php");
include_once ("CSVexport.class.php");

