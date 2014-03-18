<?php

class PagePublisher {

	// Private properties
	private $yggdrasilConfig;

	private $publishDate;
	private $pages;
	private $jsFiles;
	private $cssFiles;

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
	}

	// PUBLIC: Copy folder recursive
	private function copy_recurse($source, $destination) {
		$directory = opendir($source);

		@mkdir($destination);

		while(false !== ($file = readdir($directory))) {
			if(($file != '.') && ($file != '..')) {
				if(is_dir($source . '/' . $file)) {
					$this->copy_recurse($source . '/' . $file,$destination . '/' . $file);
				} else {
					copy($source . '/' . $file,$destination . '/' . $file);
				}
			}
		}

		closedir($directory);
	}

	// PUBLIC: Delete folder recursive
	public function delete_recurse($path, $removeFolder) {
		$directory = new DirectoryIterator($path);

		foreach($directory as $fileinfo) {
			if(!$fileinfo->isDot()) {

				if($fileinfo->isFile()) {
					unlink($fileinfo->getPathName());
				} else {
					$this->delete_recurse($fileinfo->getPathName(), true);
				}
			}
		}

		if($removeFolder) {
			rmdir($path);
		}
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

	// PRIVATE: Prepare js files
	public function prepareJSFiles() {
		$tempPath =  str_replace(DIRECTORY_SEPARATOR . "custom", "", $this->yggdrasilConfig["backend"]["rootDir"]) . DIRECTORY_SEPARATOR . "temp" . DIRECTORY_SEPARATOR . $this->yggdrasilConfig["frontend"]["jsFolder"] . DIRECTORY_SEPARATOR;

		// Create published js folder if not exists
		if(!file_exists($tempPath)) {
			mkdir($tempPath);
		}

		foreach($this->jsFiles as $mergedFile => $jsFiles) {
			$jsContent = "";

			foreach($jsFiles as $jsFile) {
				$jsContent .= file_get_contents($this->yggdrasilConfig["backend"]["rootDir"] . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR . basename($jsFile));
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
		$tempPath =  str_replace(DIRECTORY_SEPARATOR . "custom", "", $this->yggdrasilConfig["backend"]["rootDir"]) . DIRECTORY_SEPARATOR . "temp" . DIRECTORY_SEPARATOR . $this->yggdrasilConfig["frontend"]["cssFolder"] . DIRECTORY_SEPARATOR;

		// Create published css folder if not exists
		if(!file_exists($tempPath)) {
			mkdir($tempPath);
		}

		foreach($this->cssFiles as $mergedFile => $cssFiles) {
			$cssContent = "";

			foreach($cssFiles as $cssFile) {
				$cssContent .= file_get_contents($this->yggdrasilConfig["backend"]["rootDir"] . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR . basename($cssFile));
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
			$publishTempPath = str_replace(DIRECTORY_SEPARATOR . "custom", "", $this->yggdrasilConfig["backend"]["rootDir"]) . DIRECTORY_SEPARATOR . "temp" . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $publishPage["page"]);
			$publishTempFile = $publishTempPath . DIRECTORY_SEPARATOR . "index.html";

			// Create folders
			if(!file_exists($publishTempPath)) {
				mkdir($publishTempPath, 755, true);
			}

			// Refresh cache buster params
			foreach($this->jsFiles as $mergedName => $jsFile) {
				$jsFilePath = $this->yggdrasilConfig["frontend"]["rootDir"] . $this->yggdrasilConfig["frontend"]["jsFolder"] . DIRECTORY_SEPARATOR . $mergedName;

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
				$cssFilePath = $this->yggdrasilConfig["frontend"]["rootDir"] . $this->yggdrasilConfig["frontend"]["cssFolder"] . DIRECTORY_SEPARATOR . $mergedName;

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

	// PUBLIC: Publish queue
	public function publish() {
		$publishTempPath = str_replace(DIRECTORY_SEPARATOR . "custom", "", $this->yggdrasilConfig["backend"]["rootDir"]) . DIRECTORY_SEPARATOR . "temp";

		$this->copy_recurse($publishTempPath, $this->yggdrasilConfig["frontend"]["rootDir"]);
		$this->delete_recurse($publishTempPath, false);
	}

	// PUBLIC: Clear frontend
	public function clear() {
		$this->delete_recurse($this->yggdrasilConfig["frontend"]["rootDir"], false);
	}

	// PUBLIC: Clear frontend and publish queue
	public function clearAndPublish() {
		$this->clear();
		$this->publish();
	}
}

?>