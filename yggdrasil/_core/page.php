<?php

// Set viewmode
define("YGGDRASIL_VIEWMODE", 10);

// Init core
require_once "init.php";

// Get requested page url
$currentPagePath = isset($_GET["pagePath"]) ? $_GET["pagePath"] : "";

// Get page
$currentPage = new Page($currentPagePath); //10 = backend, 0 = publish, -10 = front

// Parse page
$pageParser = new PageParser($currentPage);

if($currentPage->exists()) {
	$pageParser->parse();

	$pageParser->showBackend();

	$pageParser->createCompiledFile();
	echo $pageParser->getCompiledOutput();
} else {
	$pageParser->showMessage("Seite nicht gefunden", "Die angeforderte Seite \"{$currentPagePath}\" wurde nicht gefunden.", "error");

	$pageParser->showBackend();

	echo $pageParser->getOutput();
}

?>