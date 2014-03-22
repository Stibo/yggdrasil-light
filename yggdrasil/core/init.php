<?php

error_reporting(E_ALL);

define("__DS__", DIRECTORY_SEPARATOR);

require_once  "custom/settings.php";

// Auto settings
if($yggdrasilConfig["frontend"]["mediaUrl"] == "") {
	$yggdrasilConfig["frontend"]["mediaUrl"] = $yggdrasilConfig["frontend"]["rootUrl"];
}

$yggdrasilConfig["backend"]["rootUrl"] = (($_SERVER["SERVER_PORT"] == 443) ? "https://" : "http://") . $_SERVER["SERVER_NAME"] . dirname($_SERVER["SCRIPT_NAME"]);
$yggdrasilConfig["backend"]["customDir"] = realpath("custom/") . __DS__;
$yggdrasilConfig["backend"]["tempDir"] = realpath("temp/") . __DS__;

require_once "core/classes/class.dbug.php";
require_once "core/classes/class.minifycss.php";
require_once "core/classes/class.minifyjs.php";
require_once "core/classes/class.minifyhtml.php";
require_once "core/classes/class.page.php";
require_once "core/classes/class.pageparser.php";
require_once "core/classes/class.pagepublisher.php";

?>