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

	// PRIVATE: Load page file
	private function parsePageContent() {
		$sections = array();
		$fileContent = "";

		// Get page file content
		ob_start();

		if(file_exists("custom/pages/{$this->path}/index.php")) {
			include "custom/pages/{$this->path}/index.php";
		} else {
			echo "Page not found: \"{$this->path}\"";
		}

		// Parse sections
		preg_match_all('/<y:section.*name=\"(.+)\".*>(.*)<\/y:section>/smiU', ob_get_clean(), $matches, PREG_SET_ORDER);

		foreach($matches as $match) {
			$sections[$match[1]] = trim($match[2]);
		}

		$this->sections = $sections;
	}

	// PRIVATE: Load template file
	private function parseTemplate() {
		// Get template file content
		ob_start();

		if(file_exists("custom/templates/{$this->settings["template"]}.php")) {
			include "custom/templates/{$this->settings["template"]}.php";
		} else {
			echo "Template not found: \"{$this->settings["template"]}\"";
		}

		$this->output = ob_get_clean();
	}

	// PRIVATE: Parse elements
	private function loadElement($matches) {
		$contentElementXml = str_replace(array("<y:", "</y:"), array("<", "</"), $matches[0]);
		$contentElementName = $matches[1];
		$contentElement = simplexml_load_string($contentElementXml);

		ob_start();

		if(file_exists("custom/elements/{$contentElementName}.php")) {
			include "custom/elements/{$contentElementName}.php";
		} else {
			echo "Content element \"{$contentElementName}\" not found!";
		}

		return ob_get_clean();
	}

	// PRIVATE: Parse elements
	private function parseElements() {
		$this->output = preg_replace_callback("/<y:element.*name=\"(.+)\".*>.*<\/y:element>/smiU", array($this, 'loadElement'), $this->output);
	}

	// PRIVATE: Merge js
	private function mergeJS($matches) {
		global $publish;

		$jsMinifierXml = simplexml_load_string(str_replace(array("<y:", "</y:"), array("<", "</"), $matches[0]));
		$jsFiles = "";
		$jsContent = "";

		$jsAsync = (string)$jsMinifierXml["async"];
		$jsMergedFilename = $jsMinifierXml["name"];

		foreach($jsMinifierXml->file as $jsFile) {
			if(file_exists("custom/js/{$jsFile}")) {
				if($this->publishMode) {
					$jsContent .= file_get_contents("custom/js/{$jsFile}");
				} else {
					$jsFiles .= '<script src="custom/js/' . $jsFile . '?' . time() . '"></script>';
				}
			}
		}

		if($this->publishMode) {
			//$jsContentMinified = Minify_JS_ClosureCompiler::minify($jsContent);
			$jsContentMinified = $jsContent;

			$publishPath = $publish["rootDir"] . $publish["jsFolder"] . DIRECTORY_SEPARATOR;
			$publishFile = $publishPath . $jsMergedFilename;

			// Create published js folder if not exists
			if(!file_exists($publishPath)) {
				mkdir($publishPath);
			}

			// Write minified js content
			file_put_contents($publishFile, $jsContentMinified);

			// Create link to the minified js file
			$jsFiles = '<script src="' . $publish["mediaUrl"] . $publish["jsFolder"] . '/' . $jsMergedFilename . '?' . time() . ' async="' . $jsAsync . '"></script>';
		}

		return $jsFiles;
	}

	// PRIVATE: Parse js files
	private function parseJSFiles() {
		$this->output = preg_replace_callback("/<y:minifyjs.*>.*<\/y:minifyjs>/smiU", array($this, 'mergeJS'), $this->output);
	}

	// PRIVATE: Merge css
	private function mergeCSS($matches) {
		global $publish;

		$cssMinifierXml = simplexml_load_string(str_replace(array("<y:", "</y:"), array("<", "</"), $matches[0]));
		$cssFiles = "";
		$cssContent = "";

		$cssMedia = ((string)$cssMinifierXml["media"] == "") ? "screen" : (string)$cssMinifierXml["media"];
		$cssMergedFilename = $cssMinifierXml["name"];

		// Merge css files
		foreach($cssMinifierXml->file as $cssFile) {
			if(file_exists("custom/css/{$cssFile}")) {
				if($this->publishMode) {
					$cssContent .= file_get_contents("custom/css/{$cssFile}");
				} else {
					$cssFiles .= '<link rel="stylesheet" href="custom/css/' . $cssFile . '?' . time() . '" media="' . $cssMedia . '" />';
				}
			}
		}

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

			// Create link to the minified css file
			$cssFiles = '<link rel="stylesheet" href="' . $publish["mediaUrl"] . $publish["cssFolder"] . '/' . $cssMergedFilename . '?' . time() . '" media="' . $cssMedia . '" />';
		}

		return $cssFiles;
	}

	// PRIVATE: Parse css files
	private function parseCSSFiles() {
		$this->output = preg_replace_callback("/<y:minifycss.*>.*<\/y:minifycss>/smiU", array($this, 'mergeCSS'), $this->output);
	}

	// PRIVATE: Parse html output
	private function parseHTMLOutput() {
		if($this->publishMode) {
			$this->output = Minify_HTML::minify($this->output);
		}
	}

	// PRIVATE: Show backend
	private function showBackend() {
		ob_start();

		include "core/backend/gui.php";

		$guiContent = ob_get_clean();

		$this->output = preg_replace("/(<\/body>.*<\/html>.*?)/smiU", "{$guiContent}$1", $this->output);
	}

	// PRIVATE: Publish page
	private function publishPage() {
		global $publish;

		$publishPath = $publish["rootDir"] . str_replace("/", DIRECTORY_SEPARATOR, $this->path);
		$publishFile = $publishPath . DIRECTORY_SEPARATOR . "index.html";

		// Create published css folder if not exists
		if(!file_exists($publishPath)) {
			mkdir($publishPath, 755, true);
		}

		// Write minified css content
		file_put_contents($publishFile, $this->getOutput());
	}

	// PUBLIC: Parse page
	public function parse() {
		$this->parsePageContent();
		$this->parseTemplate();
		$this->parseElements();
		$this->parseJSFiles();
		$this->parseCSSFiles();
		$this->parseHTMLOutput();

		if($this->publishMode) {
			$this->publishPage();
		} else {
			$this->showBackend();
		}
	}

	// PUBLIC: Get output
	public function getOutput() {
		return $this->output;
	}
}


// TODO: schlaue cachebuster bei mehrfach-publishing

?>