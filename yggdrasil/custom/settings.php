<?php

// Live server settings
$yggdrasilConfig = array(
	"isDev" => $_SERVER["SERVER_NAME"] == "localhost",
	"frontend" => array(
		"rootDir" => realpath("../www"),
		"rootUrl" => "http://yggdrasil.stibo.ch/",
		"mediaUrl" => "",
		"cssFolder" => "css",
		"jsFolder" => "js",
		"imgFolder" => "images",
		"ignoreFolders" => array(),
	),
	"backend" => array(
		"dateTimeFormat" => "d.m.Y H:i"
	)
);

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
		"robots.txt" => ($yggdrasilConfig["isDev"]) ? "files/robots_dev.txt" : "files/robots_live.txt",
		".htaccess" => "files/.htaccess"
	),
	"custom" => array()
);

// Developer server settings
if($yggdrasilConfig["isDev"]) {
	$yggdrasilConfig["frontend"]["rootUrl"] = "http://localhost/github/yggdrasil-light/www/";
	$yggdrasilConfig["frontend"]["mediaUrl"] = "";
}

?>