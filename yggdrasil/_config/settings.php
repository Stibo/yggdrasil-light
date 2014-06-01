<?php

// Settings

if($_SERVER["SERVER_NAME"] == "localhost") {
	define("YGGDRASIL_ENVIRONMENT", "dev");
	define("YGGDRASIL_FRONTEND_ROOT_URL", "http://yggdrasil.stibo.ch/");
} else {
	define("YGGDRASIL_ENVIRONMENT", "prod");
	define("YGGDRASIL_FRONTEND_ROOT_URL", "http://yggdrasil.stibo.ch/");
}

define("YGGDRASIL_FRONTEND_ROOT_DIR", realpath("../www") . DS);

// Default page settings

$defaultPageSettings = array(
	"template" => "content",
	"extension" => "html",
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
	),
	"dependencies" => array(
		"favicon.ico" => "files/favicon.ico",
		"robots.txt" => (YGGDRASIL_ENVIRONMENT == "dev") ? "files/robots_dev.txt" : "files/robots_live.txt",
		".htaccess" => "files/.htaccess"
	)
);

?>