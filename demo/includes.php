<?php

// This is the includes file for the application layer (as opposed to the 
// REST API).  This procedure will build the include path for all modules needed
// in the application.  It then includes the common procedures and configuration
// for the installation.

// Note:  $_SERVER['DOCUMENT_ROOT'] will return the root directory of the 
// web site.  If this application is ported into a web site (e.g. a Wordpress
// site), the document root will not be the root folder of this application.

// This includes procedure is expected to be in the root directory of the 
// PubAssist application.  The __DIR__ constant should return that path. 

#$rootPath = $_SERVER['DOCUMENT_ROOT'];  // Don't use.  Could change if ported to another web site.
$rootPath = __DIR__ ;	// The directory of this includes procedure.

// Add the root to the existing include path.
$includePath = get_include_path() ;
$includePath .= PATH_SEPARATOR . $rootPath;

// Note that the __DIR__ constant does NOT include the trailing slash
// for the folder.  So, it should be pre-pended to subfolders.
$includePath .= PATH_SEPARATOR . $rootPath . "/common";

// Include User Interface folders
$includePath .= PATH_SEPARATOR . $rootPath . "/common/ui/classes";
$includePath .= PATH_SEPARATOR . $rootPath . "/common/ui/css";
$includePath .= PATH_SEPARATOR . $rootPath . "/common/ui/js";
$includePath .= PATH_SEPARATOR . $rootPath . "/common/ui/templates";

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
include_once ("tableTop.class.php");
include_once ("tableControl.class.php");
include_once ("pageNav.class.php");
include_once ("table.class.php");
include_once ("form.class.php");
include_once ("report.class.php");
include_once ("label.class.php");
include_once ("searchField.class.php");
include_once ("searchList.class.php");
include_once ("auth.class.php");
include_once ("progressBar.class.php");
include_once ("messageBox.class.php");
include_once ("CSVexport.class.php");
include_once ("wizard.class.php");

