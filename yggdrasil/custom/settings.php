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

$googleAnalytics = "UA-xxxxxx";


?>