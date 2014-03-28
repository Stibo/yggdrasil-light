<?php

class PagePublisher {

	// Private properties
	private $yggdrasilConfig;

	private $publishDate;
	private $pages;
	private $jsFiles;
	private $cssFiles;
	private $dependencies;

	private $jsFilesPublished;
	private $cssFilesPublished;

	// Constructor
	public function __construct() {
		global $yggdrasilConfig;

		$this->yggdrasilConfig = $yggdrasilConfig;

		$this->publishDate = time();
		$this->pages = array();
		$this->jsFiles = array();
		$this->cssFiles = array();
		$this->dependencies = array();
	}

	// PUBLIC: Add page
	public function addPage($page, $pageContent) {
		$this->pages[] = array(
			"page" => $page,
			"content" => $pageContent
		);
	}

	// PUBLIC: Get pages
	public function getPages() {
		return $this->pages;
	}

	// PUBLIC: Add js files
	public function addJSFiles($mergedFile, $files) {
		if(!isset($this->jsFiles[$mergedFile])) {
			$this->jsFiles[$mergedFile] = $files;
		}
	}

	// PUBLIC: Get js files
	public function getJSFiles() {
		return $this->jsFiles;
	}

	// PUBLIC: Add css files
	public function addCSSFiles($mergedFile, $files) {
		if(!isset($this->cssFiles[$mergedFile])) {
			$this->cssFiles[$mergedFile] =  $files;
		}
	}

	// PUBLIC: Get css files
	public function getCSSFiles() {
		return $this->cssFiles;
	}

	// PUBLIC: Add dependencies
	public function addDependencies($dependencies) {
		foreach($dependencies as $dependencyKey => $dependency) {
			if(!isset($this->dependencies[$dependencyKey])) {
				$this->dependencies[$dependencyKey] =  $dependency;
			}
		}
	}

	// PUBLIC: Get dependencies
	public function getDependencies() {
		return $this->dependencies;
	}

	// PRIVATE: Prepare js files
	public function prepareJSFiles() {
		$tempPath =  $this->yggdrasilConfig["backend"]["tempDir"] . $this->yggdrasilConfig["frontend"]["jsFolder"] . __DS__;

		include "custom/globals.php";

		// Create published js folder if not exists
		if(!file_exists($tempPath)) {
			mkdir($tempPath);
		}

		foreach($this->jsFiles as $mergedFile => $jsFiles) {
			$jsContent = "";

			foreach($jsFiles as $jsFile) {
				ob_start();

				include $this->yggdrasilConfig["backend"]["customDir"] . __DS__ . "js" . __DS__ . basename($jsFile);

				$jsContent .= ob_get_clean();
			}

			$jsContentMinified = Minify_JS_ClosureCompiler::minify($jsContent);

			// Write minified js content
			file_put_contents($tempPath . $mergedFile, $jsContentMinified);

			// Set published date
			$jsFilesPublished[$mergedFile] = filemtime($tempPath . $mergedFile);
		}
	}

	// PRIVATE: Prepare css files
	public function prepareCSSFiles() {
		$tempPath =  $this->yggdrasilConfig["backend"]["tempDir"] . $this->yggdrasilConfig["frontend"]["cssFolder"] . __DS__;

		// Create published css folder if not exists
		if(!file_exists($tempPath)) {
			mkdir($tempPath);
		}

		foreach($this->cssFiles as $mergedFile => $cssFiles) {
			$cssContent = "";

			foreach($cssFiles as $cssFile) {
				ob_start();

				include $this->yggdrasilConfig["backend"]["customDir"] . __DS__ . "css" . __DS__ . basename($cssFile);

				$cssContent .= ob_get_clean();
			}

			// Init css minifier
			$cssMinifier = new CSSmin();

			// Minify css content
			$cssContentMinified = $cssMinifier->run($cssContent);

			// Write minified css content
			file_put_contents($tempPath . $mergedFile, $cssContentMinified);

			// Set published date
			$cssFilesPublished[$mergedFile] = filemtime($tempPath . $mergedFile);
		}
	}

	// PRIVATE: Publish pages
	public function preparePages() {
		foreach($this->pages as $publishPage) {
			$publishPage["content"] = Minify_HTML::minify($publishPage["content"]);

			// Get temp path
			$publishTempPath = $this->yggdrasilConfig["backend"]["tempDir"] . str_replace("/", __DS__, $publishPage["page"]->pageInfos["path"]);
			$publishTempFile = $publishTempPath . __DS__ . "index." . $publishPage["page"]->pageSettings["extension"];

			// Create folders
			if(!file_exists($publishTempPath)) {
				mkdir($publishTempPath, 755, true);
			}

			// Refresh cache buster params
			foreach($this->jsFiles as $mergedName => $jsFile) {
				$jsFilePath = $this->yggdrasilConfig["frontend"]["rootDir"] . $this->yggdrasilConfig["frontend"]["jsFolder"] . __DS__ . $mergedName;

				if(isset($jsFilesPublished[$mergedName])) {
					$jsFileLastModified = $jsFilesPublished[$mergedName];
				} elseif(file_exists($jsFilePath)) {
					$jsFileLastModified = filemtime($jsFilePath);
				} else {
					$jsFileLastModified = $this->publishDate;
				}

				$publishPage["content"] = str_replace($mergedName . "?CACHEBUSTER", $mergedName . "?{$jsFileLastModified}", $publishPage["content"]);
			}

			foreach($this->cssFiles as $mergedName => $cssFile) {
				$cssFilePath = $this->yggdrasilConfig["frontend"]["rootDir"] . $this->yggdrasilConfig["frontend"]["cssFolder"] . __DS__ . $mergedName;

				if(isset($cssFilesPublished[$mergedName])) {
					$cssFileLastModified = $cssFilesPublished[$mergedName];
				} elseif(file_exists($cssFilePath)) {
					$cssFileLastModified = filemtime($cssFilePath);
				} else {
					$cssFileLastModified = $this->publishDate;
				}

				$publishPage["content"] = str_replace($mergedName . "?CACHEBUSTER", $mergedName . "?{$cssFileLastModified}", $publishPage["content"]);
			}

			// Create index file
			file_put_contents($publishTempFile, $publishPage["content"]);
		}
	}

	// PUBLIC: Prepare dependencies
	public function prepareDependencies() {
		foreach($this->dependencies as $dependencyDestination => $dependencySource) {
			$publishTempPath = $this->yggdrasilConfig["backend"]["tempDir"] . str_replace("/", __DS__, $dependencyDestination);
			$sourcePath = $this->yggdrasilConfig["backend"]["customDir"] . str_replace("/", __DS__, $dependencySource);

			// Create folders
			if(!file_exists(dirname($publishTempPath))) {
				mkdir(dirname($publishTempPath), 755, true);
			}

			if(is_dir($sourcePath)) {
				Helper::copy_recurse($sourcePath, $publishTempPath);
			} else {
				copy($sourcePath, $publishTempPath);
			}
		}
	}

	// PUBLIC: Prepare all
	public function prepareAll() {
		$this->prepareJSFiles();
		$this->prepareCSSFiles();
		$this->preparePages();
		$this->prepareDependencies();
	}

	// PUBLIC: Publish queue
	public function publish() {
		$publishTempPath = $this->yggdrasilConfig["backend"]["tempDir"];

		Helper::copy_recurse($publishTempPath, $this->yggdrasilConfig["frontend"]["rootDir"]);
		Helper::delete_recurse($publishTempPath, false, true);
	}

	// PUBLIC: Clear frontend
	public function clear() {
		Helper::delete_recurse($this->yggdrasilConfig["frontend"]["rootDir"], false, false);
	}

	// PUBLIC: Clear frontend and publish queue
	public function clearAndPublish() {
		$this->clear();
		$this->publish();
	}
}

?>