<?php

class PagePublisher {

	// Private properties
	private static $enabled = false;

	private static $publishDate;
	private static $pages = array();
	private static $jsFiles = array();
	private static $cssFiles = array();
	private static $dependencies = array();

	private static $jsFilesPublished = array();
	private static $cssFilesPublished = array();

	private static $filesToDelete = array();
	private static $foldersToDelete = array();

	// STATIC: Enable publisher
	public static function enable() {
		self::$enabled = true;
		self::$publishDate = time();
	}

	// STATIC: Check if publisher is enabled
	public static function isEnabled() {
		return self::$enabled;
	}

	// STATIC: Add page
	public static function addPage($page, $pageContent) {
		self::$pages[] = array(
			"page" => $page,
			"content" => $pageContent
		);
	}

	// STATIC: Get pages
	public static function getPages() {
		return self::$pages;
	}

	// STATIC: Add js files
	public static function addJSFiles($mergedFile, $files) {
		if(!isset(self::$jsFiles[$mergedFile])) {
			self::$jsFiles[$mergedFile] = $files;
		}
	}

	// STATIC: Get js files
	public static function getJSFiles() {
		return self::$jsFiles;
	}

	// STATIC: Add css files
	public static function addCSSFiles($mergedFile, $files) {
		if(!isset(self::$cssFiles[$mergedFile])) {
			self::$cssFiles[$mergedFile] =  $files;
		}
	}

	// STATIC: Get css files
	public static function getCSSFiles() {
		return self::$cssFiles;
	}

	// STATIC: Add dependencies
	public static function addDependencies($dependencies) {
		foreach($dependencies as $dependencyKey => $dependency) {
			if(!isset(self::$dependencies[$dependencyKey])) {
				self::$dependencies[$dependencyKey] =  $dependency;
			}
		}
	}

	// STATIC: Get dependencies
	public static function getDependencies() {
		return self::$dependencies;
	}

	// STATIC: Prepare js files
	public static function prepareJSFiles() {
		$tempPath =  $GLOBALS["yggdrasilConfig"]["backend"]["tempDir"] . $GLOBALS["yggdrasilConfig"]["frontend"]["jsFolder"] . __DS__;

		include "custom/globals.php";

		// Create published js folder if not exists
		if(!file_exists($tempPath)) {
			mkdir($tempPath, 0755);
		}

		foreach(self::$jsFiles as $mergedFile => $jsFiles) {
			$jsContent = "";

			foreach($jsFiles as $jsFile) {
				ob_start();

				include $GLOBALS["yggdrasilConfig"]["backend"]["customDir"] . __DS__ . str_replace("/", __DS__, $jsFile);

				$jsContent .= ob_get_clean();
			}

			$jsContentMinified = Minify_JS_ClosureCompiler::minify($jsContent);

			// Write minified js content
			file_put_contents($tempPath . $mergedFile, $jsContentMinified);

			// Set published date
			$jsFilesPublished[$mergedFile] = filemtime($tempPath . $mergedFile);
		}
	}

	// STATIC: Prepare css files
	public static function prepareCSSFiles() {
		$tempPath =  $GLOBALS["yggdrasilConfig"]["backend"]["tempDir"] . $GLOBALS["yggdrasilConfig"]["frontend"]["cssFolder"] . __DS__;

		// Create published css folder if not exists
		if(!file_exists($tempPath)) {
			mkdir($tempPath, 0755);
		}

		foreach(self::$cssFiles as $mergedFile => $cssFiles) {
			$cssContent = "";

			foreach($cssFiles as $cssFile) {
				ob_start();

				include $GLOBALS["yggdrasilConfig"]["backend"]["customDir"] . __DS__ . str_replace("/", __DS__, $cssFile);

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

	// STATIC: Publish pages
	public static function preparePages() {
		foreach(self::$pages as $publishPage) {
			$publishPage["content"] = Minify_HTML::minify($publishPage["content"]);

			// Get temp path
			$publishTempPath = $GLOBALS["yggdrasilConfig"]["backend"]["tempDir"] . str_replace("/", __DS__, $publishPage["page"]->pageInfos["path"]);
			$publishTempFile = $publishTempPath . __DS__ . "index." . $publishPage["page"]->pageSettings["extension"];

			// Create folders
			if(!file_exists($publishTempPath)) {
				mkdir($publishTempPath, 0755, true);
			}

			// Refresh cache buster params
			foreach(self::$jsFiles as $mergedName => $jsFile) {
				$jsFilePath = $GLOBALS["yggdrasilConfig"]["frontend"]["rootDir"] . $GLOBALS["yggdrasilConfig"]["frontend"]["jsFolder"] . __DS__ . $mergedName;

				if(isset($jsFilesPublished[$mergedName])) {
					$jsFileLastModified = $jsFilesPublished[$mergedName];
				} elseif(file_exists($jsFilePath)) {
					$jsFileLastModified = filemtime($jsFilePath);
				} else {
					$jsFileLastModified = self::$publishDate;
				}

				$publishPage["content"] = str_replace($mergedName . "?CACHEBUSTER", $mergedName . "?{$jsFileLastModified}", $publishPage["content"]);
			}

			foreach(self::$cssFiles as $mergedName => $cssFile) {
				$cssFilePath = $GLOBALS["yggdrasilConfig"]["frontend"]["rootDir"] . $GLOBALS["yggdrasilConfig"]["frontend"]["cssFolder"] . __DS__ . $mergedName;

				if(isset($cssFilesPublished[$mergedName])) {
					$cssFileLastModified = $cssFilesPublished[$mergedName];
				} elseif(file_exists($cssFilePath)) {
					$cssFileLastModified = filemtime($cssFilePath);
				} else {
					$cssFileLastModified = self::$publishDate;
				}

				$publishPage["content"] = str_replace($mergedName . "?CACHEBUSTER", $mergedName . "?{$cssFileLastModified}", $publishPage["content"]);
			}

			// Create index file
			file_put_contents($publishTempFile, $publishPage["content"]);
		}
	}

	// STATIC: Prepare dependencies
	public static function prepareDependencies() {
		foreach(self::$dependencies as $dependencyDestination => $dependencySource) {
			$publishTempPath = $GLOBALS["yggdrasilConfig"]["backend"]["tempDir"] . str_replace("/", __DS__, $dependencyDestination);
			$sourcePath = $GLOBALS["yggdrasilConfig"]["backend"]["customDir"] . str_replace("/", __DS__, $dependencySource);

			// Create folders
			if(!file_exists(dirname($publishTempPath))) {
				mkdir(dirname($publishTempPath), 0755, true);
			}

			if(is_dir($sourcePath)) {
				Helper::copy_recurse($sourcePath, $publishTempPath);
			} else {
				copy($sourcePath, $publishTempPath);
			}
		}
	}

	// STATIC: Prepare all
	public static function prepareAll() {
		self::prepareJSFiles();
		self::prepareCSSFiles();
		self::preparePages();
		self::prepareDependencies();
	}

	// STATIC: Collect unneeded files
	public static function collectUnneededFiles() {
		// Get all unneeded files and folders
		$frontendPageIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($GLOBALS["yggdrasilConfig"]["frontend"]["rootDir"]));

		while($frontendPageIterator->valid()) {
			if(!$frontendPageIterator->isDot()) {
				// Get files
				$deleteFile = !file_exists($GLOBALS["yggdrasilConfig"]["backend"]["tempDir"] . $frontendPageIterator->getSubPathName());
				$filePath = $GLOBALS["yggdrasilConfig"]["frontend"]["rootDir"] . $frontendPageIterator->getSubPathName();

				if($deleteFile) {
					self::$filesToDelete[] = $filePath;
				}

				// Get folder
				$deleteFolder = !file_exists($GLOBALS["yggdrasilConfig"]["backend"]["tempDir"] . $frontendPageIterator->getSubPath());
				$folderPath = $GLOBALS["yggdrasilConfig"]["frontend"]["rootDir"] . $frontendPageIterator->getSubPath();

				if($deleteFolder && array_search($folderPath, self::$foldersToDelete) === false) {
					self::$foldersToDelete[] = $folderPath;
				}
			}

			$frontendPageIterator->next();
		}

		rsort(self::$foldersToDelete);
	}

	// STATIC: Publish queue
	private static function publish() {
		Helper::copy_recurse($GLOBALS["yggdrasilConfig"]["backend"]["tempDir"], $GLOBALS["yggdrasilConfig"]["frontend"]["rootDir"]);
	}

	// STATIC: Clear frontend
	private static function clearFrontend() {
		foreach(self::$filesToDelete as $file) {
			@unlink($file);
		}

		foreach(self::$foldersToDelete as $folder) {
			@rmdir($folder);
		}
	}

	// STATIC: Clear temp
	private static function clearTemp() {
		Helper::delete_recurse($GLOBALS["yggdrasilConfig"]["backend"]["tempDir"], false, true);
	}

	// STATIC: Clear frontend and publish queue
	public static function clearAndPublish() {
		self::publish();
		self::clearFrontend();
		self::clearTemp();
	}
}

?>