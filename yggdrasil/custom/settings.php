<?php

// auto: base href
// auto: X-UA-Compatible
// auto: apple-mobile-web-app-capable
// auto: msapplication-tap-highlight

$publish = array(
	"rootDir" => realpath("../") . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR,
	"cssFolder" => "css",
	"jsFolder" => "js",
	"imgFolder" => "images",
	"mediaUrl" => ""
);

$backend = array(
	"customDir" => __DIR__,
	"url" => (($_SERVER["SERVER_PORT"] == 443) ? "https://" : "http://") . $_SERVER["SERVER_NAME"] . dirname($_SERVER["SCRIPT_NAME"])
);

// Default page settings
$page = array(
	"template" => "content",
	"head" => array(
		"meta" => array(
			"title" => "Default title",
			"description" => "Default description",
			"keywords" => "Default keywords",
			"robots" => "Default robots",
		),
		"geo" => array(
			"region" => "CH-LU",
			"placename" => "Luzern",
			"position" => "47.049407;8.325877",
		),
		"og" => array(
			"type" => "Default og type",
			"site_name" => "Default og sitename",
			"title" => "Default og title",
			"description" => "Default og description",
			"url" => "Default og url",
			"image" => array(
				"Default og image 1",
				"Default og image 2",
				"Default og image 3",
			)
		),
		"canonical" => "/"
	)
);

// NEW SETTINGS

$devHost = "localhost";

$yggdrasilConfig = array(
	"frontend" => array(
		"rootDir" => realpath("../") . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR,
		//"rootUrl" => "",
		//"mediaUrl" => "",
		"cssFolder" => "css",
		"jsFolder" => "js",
		"imgFolder" => "images",
	),
	"backend" => array(
		"rootDir" => __DIR__,
		"rootUrl" => (($_SERVER["SERVER_PORT"] == 443) ? "https://" : "http://") . $_SERVER["SERVER_NAME"] . dirname($_SERVER["SCRIPT_NAME"]),
		"cssFolder" => "css",
		"jsFolder" => "js",
		"imgFolder" => "images"
	),
	"pageDefaultConfig" => array(
		"template" => "content",
		"googleAnalytics" => "",
		"head" => array(
			"meta" => array(
				"title" => "Default title",
				"description" => "Default description",
				"keywords" => "Default keywords",
				"robots" => "Default robots",
			),
			"geo" => array(
				"region" => "CH-LU",
				"placename" => "Luzern",
				"position" => "47.049407;8.325877",
			),
			"og" => array(
				"type" => "Default og type",
				"site_name" => "Default og sitename",
				"title" => "Default og title",
				"description" => "Default og description",
				"url" => "Default og url",
				"image" => array(
					"Default og image 1",
					"Default og image 2",
					"Default og image 3",
				)
			)
		)
	)
);

// Dev/live specific settings
if($_SERVER["SERVER_NAME"] == $devHost) {
	$yggdrasilConfig["frontend"]["mediaUrl"] = "";
	$yggdrasilConfig["frontend"]["rootUrl"] = "http://localhost/github/yggdrasil-light/www/";
} else {
	$yggdrasilConfig["frontend"]["mediaUrl"] = "";
	$yggdrasilConfig["frontend"]["rootUrl"] = "http://yggdrasil.stibo.ch/";
}

// Set media url to the root url
if($yggdrasilConfig["frontend"]["mediaUrl"] == "") {
	$yggdrasilConfig["frontend"]["mediaUrl"] = $yggdrasilConfig["frontend"]["rootUrl"];
}

?>