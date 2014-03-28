<?php

// Init core
require_once "core/init.php";

// Get action
$actionName = isset($_GET["action"]) ? $_GET["action"] : "";

switch($actionName) {
	// Publish single page
	case "publishpage":
		ob_start();

		$publishMode = true;

		// Create page publisher
		$pagePublisher = new PagePublisher();

		// Get current page
		$currentPage = new Page($_GET["pagePath"]);
		$currentPage->loadPageContent();

		// Parse current page
		$pageParser = new PageParser($currentPage);
		$pageParser->setPublisher($pagePublisher);
		$pageParser->parse();

		// Prepare pages
		$pagePublisher->preparePages();

		// Finish publish
		$pagePublisher->publish();

		$publisherOutput = ob_get_clean();

		header("Location: " . $yggdrasilConfig["backend"]["rootUrl"] . "?pagePath=" . $_GET["pagePath"]);
	break;

	// Publish page with subpages
	case "publishall":
		ob_start();

		$publishMode = true;

		// Create page publisher
		$pagePublisher = new PagePublisher();

		// Get current page
		$rootPage = new Page("");

		$subPagesList = $rootPage->getSubPages();

		foreach($subPagesList as $subPagePath) {
			$subPage = new Page($subPagePath);
			$subPage->loadPageContent();

			// Parse current page
			$pageParser = new PageParser($subPage);
			$pageParser->setPublisher($pagePublisher);
			$pageParser->parse();
		}

		// Prepare all
		$pagePublisher->prepareAll();

		// Finish publish
		$pagePublisher->clearAndPublish();

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