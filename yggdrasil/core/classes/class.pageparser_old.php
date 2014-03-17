<?php

class PageParser {

	// Private properties
	private $publishMode;
	private $path;
	private $name;
	private $isActive;
	private $settings;
	private $sections;
	private $output;

	// Constructor
	public function __construct($path, $publishMode = false) {
		global $page;

		$this->path = $path;
		$this->name = array_slice(explode("/", $this->path), -1);
		$this->name = $this->name[0];
		$this->isInactive = strpos($this->name, "_") === 0;
		$this->settings = $page;
		$this->sections = array();
		$this->output = "";

		$this->publishMode = $publishMode;
	}

	// PUBLIC: Get page tree
	static function getPageTree($startPage = "", $showInactive = false) {
		global $backend;

		$pagesList = array();

		$pageRoot = "custom" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR;
		$pageIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pageRoot));

		while($pageIterator->valid()) {
			if(!$pageIterator->isDot()) {
				$pagePath = str_replace("\\", "/", $pageIterator->getSubPath());
				$pageName = array_slice(explode("/", $pagePath), -1);
				$pageName = $pageName[0];

				$pageIsInactive = substr($pageName, 0, 1) == "_";

				if((($startPage != "" && strpos($pagePath, $startPage) === 0) || $startPage == "") && (!$pageIsInactive || $showInactive == true)) {
					$pagesList[] = $pagePath;
				}
			}

			$pageIterator->next();
		}

		return $pagesList;
	}
}


// TODO: schlaue cachebuster bei mehrfach-publishing

?>