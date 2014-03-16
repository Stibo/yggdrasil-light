<?php

class PagePublisher {

	// Private properties
	private $pages;
	private $jsFiles;
	private $cssFiles;

	// Constructor
	public function __construct() {
		$this->pages = array();
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

	// PRIVATE: Publish js files
	private function publishJSFiles() {
		echo "<pre>";
		echo var_dump($this->jsFiles);
		echo "</pre>";
	}

	// PRIVATE: Publish css files
	private function publishCSSFiles() {
		echo "<pre>";
		echo var_dump($this->cssFiles);
		echo "</pre>";
	}

	// PRIVATE: Publish page
	private function publishPage() {
		$this->output = Minify_HTML::minify($this->output);

		$publishPath = $publish["rootDir"] . str_replace("/", DIRECTORY_SEPARATOR, $this->path);
		$publishFile = $publishPath . DIRECTORY_SEPARATOR . "index.html";

		// Create published css folder if not exists
		if(!file_exists($publishPath)) {
			mkdir($publishPath, 755, true);
		}

		// Write minified css content
		file_put_contents($publishFile, $this->getOutput());
	}

	// PUBLIC: Publish queue
	public function publish() {
		$this->publishJSFiles();
		$this->publishCSSFiles();
	}

}

?>