<?php

class PageParser {

	// Public properties
	public $yggdrasilConfig;

	// Private properties
	private $page;
	private $pageSections;

	private $publisher;
	private $output;

	// Constructor
	public function __construct($page) {
		global $yggdrasilConfig;

		$this->yggdrasilConfig = $yggdrasilConfig;

		$this->page = $page;
		$this->pageSections = array();

		$this->publisher = false;
		$this->output = "";
	}

	// PUBLIC: Set publisher
	public function setPublisher($publisher) {
		$this->publisher = $publisher;
	}

	// PRIVATE: Parse page sections
	private function parsePageSections() {
		// Parse sections
		preg_match_all('/<y:section.*name=\"(.+)\".*>(.*)<\/y:section>/smiU', $this->page->getContent(), $sectionMatches, PREG_SET_ORDER);

		foreach($sectionMatches as $sectionMatch) {
			$this->pageSections[$sectionMatch[1]] = trim($sectionMatch[2]);
		}
	}

	// PRIVATE: Parse template
	private function parseTemplate() {
		ob_start();

		if(file_exists("custom/templates/{$this->page->config["template"]}.php")) {
			include "custom/templates/{$this->page->config["template"]}.php";
		} else {
			echo "Template not found: \"{$this->page->config["template"]}\"";
		}

		$this->output = ob_get_clean();
	}

	// PRIVATE: Parse elements
	private function parseElements() {
		$this->output = preg_replace_callback("/<y:element.*name=\"(.+)\".*>.*<\/y:element>/smiU", function($elementMatches) {

			$contentElementName = $elementMatches[1];
			$contentElement = simplexml_load_string(str_replace(array("<y:", "</y:"), array("<", "</"), $elementMatches[0]));

			ob_start();

			if(file_exists("custom/elements/{$contentElementName}.php")) {
				include "custom/elements/{$contentElementName}.php";
			} else {
				echo "Content element \"{$contentElementName}\" not found!";
			}

			return ob_get_clean();

		}, $this->output);
	}

	// PRIVATE: Parse js files
	private function getJSFiles($jsMatches) {
		$jsXml = simplexml_load_string(str_replace(array("<y:", "</y:"), array("<", "</"), $jsMatches[0]));
		$jsMergedFile = (string)$jsXml["name"];
		$jsAsync = (string)$jsXml["async"];

		$jsFiles = array();
		$jsOutput = "";

		foreach($jsXml->file as $sourceJsFile) {
			if(file_exists("custom/js/{$sourceJsFile}")) {
				if($this->publisher !== false) {
					$jsFiles[] = "custom/js/{$sourceJsFile}";
				} else {
					$jsOutput .= '<script src="custom/js/' . $sourceJsFile . '?' . time() . '"></script>';
				}
			}
		}

		if($this->publisher !== false) {
			$this->publisher->addJSFiles($jsMergedFile, $jsFiles);

			$jsFrontendFolder = $this->yggdrasilConfig["frontend"]["mediaUrl"] . $this->yggdrasilConfig["frontend"]["jsFolder"];
			$jsFrontendFile = "{$jsFrontendFolder}/{$jsMergedFile}?CACHEBUSTER";

			$jsOutput = '<script src="' . $jsFrontendFile . '"' . ($jsAsync != "" ? ' async="' . $jsAsync . '"' : "" ) . '></script>';
		}

		return $jsOutput;
	}

	private function parseJSFiles() {
		$this->output = preg_replace_callback("/<y:minifyjs.*>.*<\/y:minifyjs>/smiU", array($this, 'getJSFiles'), $this->output);
	}

	// PRIVATE: Parse css files
	private function getCSSFiles($cssMatches) {
		$cssXml = simplexml_load_string(str_replace(array("<y:", "</y:"), array("<", "</"), $cssMatches[0]));
		$cssMergedFile = (string)$cssXml["name"];
		$cssMedia = ((string)$cssXml["media"] == "") ? "screen" : (string)$cssXml["media"];

		$cssFiles = array();
		$cssOutput = "";

		foreach($cssXml->file as $sourceCssFile) {
			if(file_exists("custom/css/{$sourceCssFile}")) {
				if($this->publisher !== false) {
					$cssFiles[] = "custom/css/{$sourceCssFile}";
				} else {
					$cssOutput .= '<script src="custom/css/' . $sourceCssFile . '?' . time() . '" media="' . $cssMedia . '"></script>';
				}
			}
		}

		if($this->publisher !== false) {
			$this->publisher->addCSSFiles($cssMergedFile, $cssFiles);

			$cssFrontendFolder = $this->yggdrasilConfig["frontend"]["mediaUrl"] . $this->yggdrasilConfig["frontend"]["cssFolder"];
			$cssFrontendFile = "{$cssFrontendFolder}/{$cssMergedFile}?CACHEBUSTER";

			$cssOutput = '<script src="' . $cssFrontendFile . '" media="' . $cssMedia . '"></script>';
		}

		return $cssOutput;
	}

	private function parseCSSFiles() {
		$this->output = preg_replace_callback("/<y:minifycss.*>.*<\/y:minifycss>/smiU", array($this, 'getCSSFiles'), $this->output);
	}

	// PUBLIC: Show backend
	public function showBackend() {
		ob_start();

		include "core/backend/gui.php";

		$guiContent = ob_get_clean();

		$this->output = preg_replace("/(<\/body>.*<\/html>.*?)/smiU", "{$guiContent}$1", $this->output);
	}


	// PUBLIC: Init parse
	public function parse() {
		$this->parsePageSections();

		if(count($this->pageSections) > 0) {
			$this->parseTemplate();
			$this->parseElements();
			$this->parseJSFiles();
			$this->parseCSSFiles();

			if($this->publisher !== false) {
				$this->publisher->addPage($this->page->path, $this->output);
			}
		} else {
			echo "Page does not have any sections!";
		}
	}

	// PUBLIC: Get output
	public function getOutput() {
		return $this->output;
	}

}

?>