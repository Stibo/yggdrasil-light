<?php

// Init core
require_once "init.php";

// Get requested page url
$currentPagePath = isset($_GET["pagePath"]) ? $_GET["pagePath"] : "";

// Create publisher
//$pagePublisher = new PagePublisher();

// Get page
$currentPage = new Page($currentPagePath);
$currentPage->loadPageContent();

// Parse page
$pageParser = new PageParser($currentPage);
//$pageParser->setPublisher($pagePublisher);
$pageParser->parse();
$pageParser->showBackend();

// Publish page
//$pagePublisher->publish();

echo $pageParser->getOutput();

?>