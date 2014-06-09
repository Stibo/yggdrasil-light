<?php

// Init core
require_once "init.php";

// Get requested page url
$currentPagePath = isset($_GET["pagePath"]) ? $_GET["pagePath"] : "";

// Get page
$currentPage = new Page($currentPagePath);

$currentPage->setViewmode(10);

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