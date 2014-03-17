<?php

// Init core
require_once "init.php";

// Get action
$actionName = isset($_GET["action"]) ? $_GET["action"] : "";

switch($actionName) {
	// Publish single page
	case "publishpage":
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
		//$pagePublisher->prepareJSFiles();
		//$pagePublisher->prepareCSSFiles();

		// Finish publish
		$pagePublisher->publish();

		header("Location: " . $yggdrasilConfig["backend"]["rootUrl"] . "/?pagePath=" . $_GET["pagePath"]);
	break;

	// Publish page with subpages
	case "publishall":
		// Create page publisher
		$pagePublisher = new PagePublisher();

		// Get current page
		$currentPage = new Page($_GET["pagePath"]);
		$currentPage->loadPageContent();

		// Parse current page
		$pageParser = new PageParser($currentPage);
		$pageParser->setPublisher($pagePublisher);
		$pageParser->parse();

		// Get subpages
		$currentSubPages = $currentPage->getSubpages();

		// Parse subpages
		foreach($currentSubPages as $subPage) {
			// Parse current page
			$subPageParser = new PageParser($subPage);
			$subPageParser->setPublisher($pagePublisher);
			$subPageParser->parse();
		}

		// Prepare pages
		$pagePublisher->preparePages();

		// Finish publish
		$pagePublisher->publish();

		header("Location: " . $yggdrasilConfig["backend"]["rootUrl"] . "/?pagePath=" . $_GET["pagePath"]);
	break;

	// Action not found
	default:
		echo "Action not found";

	break;
}

?>