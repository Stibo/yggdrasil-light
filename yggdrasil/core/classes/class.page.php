<?php

class Page {

	// Public properties
	public $pageSettings;
	public $pageInfos;

	public $redirect;

	// Private properties
	private $yggdrasilConfig;
	private $content = "";

	// Constructor
	public function __construct($pagePath) {
		global $yggdrasilConfig, $defaultPageSettings;

		// Get yggdrasil config
		$this->yggdrasilConfig = $yggdrasilConfig;

		// Get default page settings
		$this->pageSettings = $defaultPageSettings;

		// Get page path and name
		$this->pageInfos["path"] = preg_replace("/[^a-z0-9\-_\/]/i", "", trim($pagePath, "/ \t\n\r\0\x0B"));
		$explodedPath = explode("/", $this->pageInfos["path"]);
		$this->pageInfos["name"] = array_pop($explodedPath);

		// Get full path to the frontend page
		$this->pageInfos["frontendDir"] = $yggdrasilConfig["frontend"]["rootDir"] . str_replace("/", __DS__, $this->pageInfos["path"]) . __DS__;
		$indexFile = glob("{$this->pageInfos["frontendDir"]}index.*");
		$this->pageInfos["frontendFile"] = array_shift($indexFile);

		// Get full path to the backend page
		$this->pageInfos["backendDir"] = $yggdrasilConfig["backend"]["pageDir"] . str_replace("/", __DS__, $this->pageInfos["path"]) . __DS__;
		$this->pageInfos["backendFile"] = $this->pageInfos["backendDir"] . "index.php";

		// Set base url
		$this->pageInfos["baseUrl"] = PagePublisher::isEnabled() ? $yggdrasilConfig["frontend"]["rootUrl"] : $yggdrasilConfig["backend"]["rootUrl"] ;

		if(!file_exists($this->pageInfos["backendFile"])) {
			$this->pageInfos = null;
		}
	}

	// PRIVATE: Set redirect
	private function setRedirect($redirect) {
		if(!isset($redirect["type"])) {
			$redirect["type"] = 301;
		}

		$this->redirect = $redirect;
	}

	// PUBLIC: Check if page is active
	public function isActive() {
		return (strpos($this->pageInfos["name"], "_") !== 0);
	}

	// PUBLIC: Get publish date
	public function getPublishDate($format = false) {
		// Check if page exists, else returns -1 as date
		if(file_exists($this->pageInfos["frontendFile"])) {
			$publishDate = filemtime($this->pageInfos["frontendFile"]);

			// Format date if needed
			if($format) {
				$publishDate = date($this->yggdrasilConfig["backend"]["dateTimeFormat"], $publishDate);
			}
		} else {
			$publishDate = -1;
		}

		return $publishDate;
	}

	// PUBLIC: Load page content
	public function loadPageContent() {
		ob_start();

		$pageInfos = $this->pageInfos;
		$pageSettings = &$this->pageSettings;

		include "../custom/globals.php";

		if(file_exists($this->pageInfos["backendFile"])) {
			include $this->pageInfos["backendFile"];
		} else {
			echo "Page not found: \"/{$this->pageInfos["path"]}\"";
		}

		if(isset($redirect)) {
			$this->setRedirect($redirect);
		}

		$this->content = ob_get_clean();
	}

	// PUBLIC: Get content
	public function getContent() {
		return $this->content;
	}

	// PUBLIC: Get subpages
	public function getSubPages($showInactive = false) {
		$pagesList = array();

		$pageIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->yggdrasilConfig["backend"]["pageDir"]));

		while($pageIterator->valid()) {
			if(!$pageIterator->isDot()) {
				$pagePath = str_replace("\\", "/", $pageIterator->getSubPath());
				$pageName = array_slice(explode("/", $pagePath), -1);
				$pageName = $pageName[0];

				$pageIsInactive = substr($pageName, 0, 1) == "_";

				if((($this->pageInfos["path"] != "" && strpos($pagePath, $this->pageInfos["path"]) === 0) || $this->pageInfos["path"] == "") && (!$pageIsInactive || $showInactive == true)) {
					$pagesList[] = $pagePath;
				}
			}

			$pageIterator->next();
		}

		sort($pagesList);

		return $pagesList;
	}

	// PUBLIC: Toggle page
	public function toggle() {
		$newPagePath = "";

		if($this->isActive()) {
			$newPagePath = $this->disable();
		} else {
			$newPagePath = $this->enable();
		}

		return $newPagePath;
	}

	// PUBLIC: Enable page
	public function enable() {
		// Rename backend folder
		$newBackendDir = implode(__DS__, array_slice(explode("/", $this->pageInfos["path"]), 0, -1)) . __DS__ . substr($this->pageInfos["name"], 1);
		$newPagePath = str_replace(__DS__, "/", $newBackendDir);

		@rename($this->pageInfos["backendDir"], $this->yggdrasilConfig["backend"]["pageDir"] . $newBackendDir);

		return $newPagePath;
	}

	// PUBLIC: Disable page
	public function disable() {
		// Rename backend folder
		$newBackendDir = implode(__DS__, array_slice(explode("/", $this->pageInfos["path"]), 0, -1)) . __DS__ . "_" . $this->pageInfos["name"];
		$newPagePath = str_replace(__DS__, "/", $newBackendDir);

		@rename($this->pageInfos["backendDir"], $this->yggdrasilConfig["backend"]["pageDir"] . $newBackendDir);

		// Remove folder from frontend if exists
		if(file_exists($this->pageInfos["frontendDir"])) {
			Helper::delete_recurse($this->pageInfos["frontendDir"], true);
		}

		return $newPagePath;
	}
}

?>