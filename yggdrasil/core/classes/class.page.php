<?php

class Page {

	// Public properties
	public $config;

	public $path;
	public $name;

	public $frontendDir;
	public $frontendFile;

	public $backendDir;
	public $backendFile;

	// Private properties
	private $content;

	// Constructor
	public function __construct($pagePath) {
		global $yggdrasilConfig;

		// Get default page config
		$this->config = $yggdrasilConfig["pageDefaultConfig"];

		// Get page path and name
		$this->path = preg_replace("/[^a-z0-9\-_\/]/i", "", $pagePath);
		$this->name = array_pop(explode("/", $this->path));

		// Get full path to the frontend page
		$this->frontendDir = $yggdrasilConfig["frontend"]["rootDir"] . __DS__ . str_replace("/", __DS__, $this->path) . __DS__;
		$this->frontendFile = $this->frontendDir . "index.html";

		// Get full path to the backend page
		$this->backendDir = $yggdrasilConfig["backend"]["customDir"] . __DS__ . "pages" . __DS__ . str_replace("/", __DS__, $this->path) . __DS__;
		$this->backendFile = $this->backendDir . "index.php";

		// Init content
		$this->content = "";
	}

	// PUBLIC: Check if page is active
	public function isActive() {
		return (strpos($this->name, "_") !== 0);
	}

	// PUBLIC: Get publish date
	public function getPublishDate($format = false) {
		$pageFile = $this->frontendDir;

		// Check if page exists, else returns -1 as date
		if(file_exists($this->frontendFile)) {
			$publishDate = filemtime($this->frontendFile);

			// Format date if needed
			if($format) {
				$publishDate = date("d.m.Y H:i", $publishDate);
			}
		} else {
			$publishDate = -1;
		}

		return $publishDate;
	}

	// PUBLIC: Load page content
	public function loadPageContent() {
		ob_start();

		if(file_exists($this->backendFile)) {
			include $this->backendFile;
		} else {
			echo "Page not found: \"/{$this->path}\"";
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

		$pageRoot = "custom" . __DS__ . "pages" . __DS__;
		$pageIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pageRoot));

		while($pageIterator->valid()) {
			if(!$pageIterator->isDot()) {
				$pagePath = str_replace("\\", "/", $pageIterator->getSubPath());
				$pageName = array_slice(explode("/", $pagePath), -1);
				$pageName = $pageName[0];

				$pageIsInactive = substr($pageName, 0, 1) == "_";

				if(substr($pageName, 0, 1) != "+" && (($this->path != "" && strpos($pagePath, $this->path) === 0) || $this->path == "") && (!$pageIsInactive || $showInactive == true)) {
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