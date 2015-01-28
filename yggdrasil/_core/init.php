<?php

define("DS", DIRECTORY_SEPARATOR);

// Get main settings
require_once  "errorhandling.php";
require_once  str_replace("_core", "_config", __DIR__) . DS . "settings.php";
require_once  "settings-default.php";


// Add the backendfolder to the ignore list if in www root
/*if(strpos($yggdrasilConfig["backend"]["rootDir"], $yggdrasilConfig["frontend"]["rootDir"]) !== false) {
	$yggdrasilConfig["frontend"]["ignoreFolders"][] = array_pop(explode(__DS__, $yggdrasilConfig["backend"]["rootDir"]));
}

// Set frontend directories to ignore
$yggdrasilConfig["frontend"]["ignoreDirs"] = array();

foreach($yggdrasilConfig["frontend"]["ignoreFolders"] as $folderName) {
	$yggdrasilConfig["frontend"]["ignoreDirs"][] = $yggdrasilConfig["frontend"]["rootDir"] . $folderName;
}


*/

// Inclure globals
require_once  str_replace("_core", "_config", __DIR__) . DS . "globals.php";


// Include classes
require_once "classes/class.dbug.php";
require_once "classes/class.minifycss.php";
require_once "classes/class.minifyjs.php";
require_once "classes/class.minifyhtml.php";
require_once "classes/class.helper.php";
require_once "classes/class.page.php";
require_once "classes/class.pageparser.php";
require_once "classes/class.pagepublisher.php";
require_once "classes/class.link.php";

//require_once "classes/class.image.php";
//require_once "classes/class.file.php";


?>