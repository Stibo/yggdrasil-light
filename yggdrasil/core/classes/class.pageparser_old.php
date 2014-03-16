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

	// PRIVATE: Merge js
	private function mergeJS($matches) {

		if($this->publishMode) {
			$jsContentMinified = Minify_JS_ClosureCompiler::minify($jsContent);

			$publishPath = $publish["rootDir"] . $publish["jsFolder"] . DIRECTORY_SEPARATOR;
			$publishFile = $publishPath . $jsMergedFilename;

			// Create published js folder if not exists
			if(!file_exists($publishPath)) {
				mkdir($publishPath);
			}

			// Write minified js content
			file_put_contents($publishFile, $jsContentMinified);
		}

		return $jsFiles;
	}


	// PRIVATE: Merge css
	private function mergeCSS($matches) {

		if($this->publishMode) {
			// Init css minifier
			$cssMinifier = new CSSmin();

			// Minify css content
			$cssContentMinified = $cssMinifier->run($cssContent);

			$publishPath = $publish["rootDir"] . $publish["cssFolder"] . DIRECTORY_SEPARATOR;
			$publishFile = $publishPath . $cssMergedFilename;

			// Create published css folder if not exists
			if(!file_exists($publishPath)) {
				mkdir($publishPath);
			}

			// Write minified css content
			file_put_contents($publishFile, $cssContentMinified);
		}

		return $cssFiles;
	}
}


// TODO: schlaue cachebuster bei mehrfach-publishing

?>