<?php

define("DS", DIRECTORY_SEPARATOR);

// Get main settings
require_once  "./_config/settings.php";
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
require_once  "./_config/globals.php";


// Include classes
require_once "classes/class.dbug.php";
require_once "classes/class.minifycss.php";
require_once "classes/class.minifyjs.php";
require_once "classes/class.minifyhtml.php";
require_once "classes/class.helper.php";
require_once "classes/class.page.php";
require_once "classes/class.pageparser.php";
require_once "classes/class.pagepublisher.php";

//require_once "classes/class.link.php";
//require_once "classes/class.image.php";
//require_once "classes/class.file.php";


?>