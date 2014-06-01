<?php

// Init core
require_once "init.php";

// Get requested page url
$currentPagePath = isset($_GET["pagePath"]) ? $_GET["pagePath"] : "";

// Get page
$currentPage = new Page($currentPagePath);

// Parse page
$pageParser = new PageParser($currentPage);

if($currentPage->exists()) {
	$pageParser->parse();
} else {
	$pageParser->setOutput("Site not found.");
}

$pageParser->showBackend();

$pageParser->createCompiledFile();
$pageParser->getCompiledFile();

?>