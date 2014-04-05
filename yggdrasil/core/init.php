<?php

error_reporting(E_ALL);

define("__DS__", DIRECTORY_SEPARATOR);

require_once  "../custom/settings.php";

// Auto-generared backend settings
$yggdrasilConfig["backend"]["rootUrl"] = (($_SERVER["SERVER_PORT"] == 443) ? "https://" : "http://") . $_SERVER["SERVER_NAME"] . dirname($_SERVER["SCRIPT_NAME"]);
$yggdrasilConfig["backend"]["rootDir"] = realpath("../");
$yggdrasilConfig["backend"]["customDir"] = realpath("../custom/") . __DS__;
$yggdrasilConfig["backend"]["tempDir"] = realpath("../temp/") . __DS__;
$yggdrasilConfig["backend"]["pageDir"] = realpath("../custom/pages/") . __DS__;
$yggdrasilConfig["backend"]["templateDir"] = realpath("../custom/templates/") . __DS__;
$yggdrasilConfig["backend"]["snippetDir"] = realpath("../custom/snippets/") . __DS__;
$yggdrasilConfig["backend"]["phpDir"] = realpath("../custom/php/") . __DS__;

$yggdrasilConfig["backend"]["imagesDir"] = realpath("../custom/img/") . __DS__; // REMOVE THAT SHIT

// Add trailing slash to the backend rooturl
if(substr($yggdrasilConfig["backend"]["rootUrl"], -1) != "/") {
	$yggdrasilConfig["backend"]["rootUrl"] = $yggdrasilConfig["backend"]["rootUrl"] . "/";
}

// Set the correct backend root url
if(substr($yggdrasilConfig["backend"]["rootUrl"], -8) != "/custom/") {
	$yggdrasilConfig["backend"]["rootUrl"] = substr($yggdrasilConfig["backend"]["rootUrl"], 0, -6) . "/custom/";
}

// Set mediaurl to the frontend root url if not defined
if($yggdrasilConfig["frontend"]["mediaUrl"] == "") {
	$yggdrasilConfig["frontend"]["mediaUrl"] = $yggdrasilConfig["frontend"]["rootUrl"];
}

// Add trailing slash/backslash to the frontend rootdir
if(substr($yggdrasilConfig["frontend"]["rootDir"], -1) != __DS__) {
	$yggdrasilConfig["frontend"]["rootDir"] = $yggdrasilConfig["frontend"]["rootDir"] . __DS__;
}

// Add the backendfolder to the ignore list if in www root
if(strpos($yggdrasilConfig["backend"]["rootDir"], $yggdrasilConfig["frontend"]["rootDir"]) !== false) {
	$yggdrasilConfig["frontend"]["ignoreFolders"][] = array_pop(explode(__DS__, $yggdrasilConfig["backend"]["rootDir"]));
}

// Set frontend directories to ignore
$yggdrasilConfig["frontend"]["ignoreDirs"] = array();

foreach($yggdrasilConfig["frontend"]["ignoreFolders"] as $folderName) {
	$yggdrasilConfig["frontend"]["ignoreDirs"][] = $yggdrasilConfig["frontend"]["rootDir"] . $folderName;
}

require_once  "../custom/globals.php";

require_once "classes/class.dbug.php";
require_once "classes/class.minifycss.php";
require_once "classes/class.minifyjs.php";
require_once "classes/class.minifyhtml.php";
require_once "classes/class.helper.php";
require_once "classes/class.page.php";
require_once "classes/class.pageparser.php";
require_once "classes/class.pagepublisher.php";
require_once "classes/class.link.php";
require_once "classes/class.image.php";
require_once "classes/class.file.php";

?>