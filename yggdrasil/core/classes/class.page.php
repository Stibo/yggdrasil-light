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
		$this->pageInfos["path"] = preg_replace("/[^a-z0-9\-_\/]/i", "", $pagePath);
		$this->pageInfos["name"] = array_pop(explode("/", $this->pageInfos["path"]));

		// Get full path to the frontend page
		$this->pageInfos["frontendDir"] = $yggdrasilConfig["frontend"]["rootDir"] . str_replace("/", __DS__, $this->pageInfos["path"]) . __DS__;
		$this->pageInfos["frontendFile"] = $this->pageInfos["frontendDir"] . "index.html";

		// Get full path to the backend page
		$this->pageInfos["backendDir"] = $yggdrasilConfig["backend"]["pagesDir"] . str_replace("/", __DS__, $this->pageInfos["path"]) . __DS__;
		$this->pageInfos["backendFile"] = $this->pageInfos["backendDir"] . "index.php";
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

		$pageIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->yggdrasilConfig["backend"]["pagesDir"]));

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
}

?>