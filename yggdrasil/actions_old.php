<?php

// Init core
require_once "core/init.php";

// Get action
$actionName = isset($_GET["action"]) ? $_GET["action"] : "";
$pagePath = preg_replace("/[^a-z0-9\-_\/]/i", "", $_GET["pagePath"]);

switch($actionName) {
	// Toggle page
	case "togglepage":

		$pageName = array_slice(explode("/", $pagePath), -1);
		$pageName = $pageName[0];

		$pageIsInactive = strpos($pageName, "_") === 0;

		$pagePath = implode(__DS__, array_slice(explode("/", $pagePath), 0, -1));
		$pageDir = "custom" . __DS__ . "pages" . __DS__ . $pagePath . __DS__;

		if($pageIsInactive) {
			$oldName = $pageName;
			$newName = substr($pageName, 1);
		} else {
			$oldName = $pageName;
			$newName = "_" . $pageName;
		}

		@rename($pageDir . $oldName, $pageDir . $newName);

		if($pageIsInactive) {
			$pageTree = PageParser::getPageTree(str_replace(__DS__, "/", $pagePath) . "/" . $newName);

			foreach($pageTree as $currentPage) {
				$pageParser = new PageParser($currentPage, true);
				$pageParser->parse();
			}
		} else {
			/*$pageTree = array_reverse(PageParser::getPageTree(str_replace(__DS__, "/", $pagePath) . "/" . $oldName, true));
			echo var_dump(str_replace(__DS__, "/", $pagePath) . "/" . $oldName);

			foreach($pageTree as $pageDelete) {
				//unlink($publish["rootDir"] . str_replace("/", __DS__, $pageDelete) . __DS__ . "index.html");
				echo "unlink: " . $publish["rootDir"] . str_replace("/", __DS__, $pageDelete) . __DS__ . "index.html";
				//rmdir($publish["rootDir"] . str_replace("/", __DS__, $pageDelete));
				echo "rmdir: " . $publish["rootDir"] . str_replace("/", __DS__, $pageDelete);
			}

			die();*/
		}

		header("Location: " . $backend["url"] . "/?pagePath=" . str_replace(__DS__, "/", $pagePath) . "/" . $newName);

	break;

	// Publish single page
	case "publishpage":

		$pageParser = new PageParser($pagePath, true);
		$pageParser->parse();


		header("Location: " . $backend["url"] . "/?pagePath=" . $pagePath);
		//echo "page published: \"/{$pagePath}\"<br />";

	break;

	// Publish single page and all subpages
	case "publishsubpages":

		$pageTree = PageParser::getPageTree($pagePath);

		foreach($pageTree as $currentPage) {
			$pageParser = new PageParser($currentPage, true);
			$pageParser->parse();

			echo "page published: \"/{$currentPage}\"<br />";
		}

	break;

	// Publish all pages
	case "publishall":

		echo "publish all<hr />";

		$pageTree = PageParser::getPageTree();

		foreach($pageTree as $currentPage) {
			$pageParser = new PageParser($currentPage, true);
			$pageParser->parse();

			echo "page published: \"/{$currentPage}\"<br />";
		}

	break;

	// Action not found
	default:
		echo "Action not found";

	break;
}

?>