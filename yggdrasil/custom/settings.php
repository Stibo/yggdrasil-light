<?php

// auto: base href
// auto: X-UA-Compatible
// auto: apple-mobile-web-app-capable
// auto: msapplication-tap-highlight

// Dev host name
$devHost = "localhost";

// Live server settings
$yggdrasilConfig = array(
	"frontend" => array(
		"rootDir" => realpath("../www"),
		"rootUrl" => "http://yggdrasil.stibo.ch/",
		"mediaUrl" => "",
		"cssFolder" => "css",
		"jsFolder" => "js",
		"imgFolder" => "images"
	),
	"backend" => array(
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
				"robots" => "Default robots"
			),
			"geo" => array(
				"region" => "CH-LU",
				"placename" => "Luzern",
				"position" => "47.049407;8.325877"
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
					"Default og image 3"
				)
			)
		)
	)
);

// Developer server settings
if($_SERVER["SERVER_NAME"] == $devHost) {
	$yggdrasilConfig["frontend"]["rootUrl"] = "http://localhost/github/yggdrasil-light/www/";
	$yggdrasilConfig["frontend"]["mediaUrl"] = "";
}

?>