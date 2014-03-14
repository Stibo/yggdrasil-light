<?php

// Init cms
require_once "core/init.php";

// Get requested page url
$page = isset($_GET["page"]) ? $_GET["page"] : "";
$page = preg_replace("/[^a-z0-9\-\/]/i", "", $page);

// Start output buffer
ob_start();

// Get page
if(file_exists("custom/page/{$page}/index.php")) {
	include "custom/page/{$page}/index.php";
} else {
	echo "Page not found: {$page}";
}

// Get output buffer
$rawOutput = ob_get_clean();

// Parse output
$outputParser = new ContentElement($rawOutput);

$outputParser->parseOutput();

$output = $outputParser->getOutput();

echo $output;

?>

