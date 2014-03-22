<?php

// Init core
require_once "core/init.php";

// Get requested page url
$currentPagePath = isset($_GET["pagePath"]) ? $_GET["pagePath"] : "";

// Get page
$currentPage = new Page($currentPagePath);
$currentPage->loadPageContent();

// Parse page
$pageParser = new PageParser($currentPage);
$pageParser->parse();
$pageParser->showBackend();

echo $pageParser->getOutput();

?>