<?php

// Init core
require_once "init.php";

set_time_limit($yggdrasilConfig["backend"]["actionTimeout"]);

// Get action
$actionName = isset($_GET["action"]) ? $_GET["action"] : "";

switch($actionName) {
	// Publish single page
	case "publishpage":
		ob_start();

		// Enable publisher
		PagePublisher::enable();

		// Get current page
		$currentPage = new Page($_GET["pagePath"]);
		$currentPage->loadPageContent();

		// Parse current page
		$pageParser = new PageParser($currentPage);
		$pageParser->parse();

		// Prepare pages
		PagePublisher::preparePages();

		// Finish publish
		PagePublisher::publish();

		$publisherOutput = ob_get_clean();

		header("Location: " . $yggdrasilConfig["backend"]["rootUrl"] . "?pagePath=" . $_GET["pagePath"]);
	break;

	// Publish page with subpages
	case "publishall":
		ob_start();

		PagePublisher::enable();

		// Get current page
		$rootPage = new Page("");

		$subPagesList = $rootPage->getSubPages();

		foreach($subPagesList as $subPagePath) {
			$subPage = new Page($subPagePath);
			$subPage->loadPageContent();

			// Parse current page
			$pageParser = new PageParser($subPage);
			$pageParser->parse();
		}

		// Prepare all
		PagePublisher::prepareAll();

		// Check for unneeded files
		PagePublisher::collectUnneededFiles();

		// Finish publish
		PagePublisher::clearAndPublish();

		$publisherOutput = ob_get_clean();

		header("Location: " . $yggdrasilConfig["backend"]["rootUrl"] . "?pagePath=" . $_GET["pagePath"]);
	break;

	// Toggle page
	case "togglepage":
		ob_start();

		// Get current page
		$currentPage = new Page($_GET["pagePath"]);

		$newPagePath = $currentPage->toggle();

		$publisherOutput = ob_get_clean();

		header("Location: " . $yggdrasilConfig["backend"]["rootUrl"] . "?pagePath=" . $newPagePath);
	break;

	// Action not found
	default:
		echo "Action not found";

	break;
}

?>