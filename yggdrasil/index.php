<?php

// Init core
require_once "init.php";

// Get requested page url
$pagePath = isset($_GET["pagePath"]) ? $_GET["pagePath"] : "";
$pagePath = preg_replace("/[^a-z0-9\-_\/]/i", "", $pagePath);

// Parse page
$pageParser = new PageParser($pagePath);
$pageParser->parse();

echo $pageParser->getOutput();

?>