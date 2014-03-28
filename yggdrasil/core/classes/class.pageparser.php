<?php

class PageParser {

	// Public properties
	private $yggdrasilConfig;

	private $page;
	private $pageSections = array();
	private $pageBaseUrl;

	private $publisher = false;
	private $output = "";

	// Constructor
	public function __construct($page) {
		global $yggdrasilConfig;

		$this->yggdrasilConfig = $yggdrasilConfig;
		$this->page = $page;
		$this->pageBaseUrl = $this->yggdrasilConfig["backend"]["rootUrl"];
	}

	// PRIVATE: Clean custom tag
	private function cleanCustomTag($customTag) {
		return str_replace(array("<y:", "</y:"), array("<", "</"), $customTag);
	}

	// PUBLIC: Set publisher
	public function setPublisher($publisher) {
		$this->publisher = $publisher;
		$this->pageBaseUrl = $this->yggdrasilConfig["frontend"]["rootUrl"];
	}

	// PRIVATE: Parse page sections
	private function parsePageSections() {
		preg_match_all('/<y:section.*name=\"(.+)\".*>(.*)<\/y:section>/smiU', $this->page->getContent(), $sectionMatches, PREG_SET_ORDER);

		foreach($sectionMatches as $sectionMatch) {
			$this->pageSections[$sectionMatch[1]] = trim($sectionMatch[2]);
		}
	}

	// PRIVATE: Parse template
	private function parseTemplate() {

		// Set page infos and settings
		$pageInfos = $this->page->pageInfos;
		$pageSettings = &$this->page->pageSettings;
		$pageBaseUrl = $this->pageBaseUrl;
		$pageSections = $this->pageSections;

		include "custom/globals.php";

		ob_start();

		if(file_exists("custom/templates/{$this->page->pageSettings["template"]}.php")) {
			include "custom/templates/{$this->page->pageSettings["template"]}.php";
		} else {
			echo "Template not found: \"{$this->page->pageSettings["template"]}\"";
		}

		$this->output = ob_get_clean();
	}

	// PRIVATE: Parse snippets
	private function getSnippets($snippetMatches) {

		// ?? yggdrasilconfig
		// ?? page

		$snippet = simplexml_load_string($this->cleanCustomTag($snippetMatches[0]));
		$snippetName = $snippet["name"];

		include "custom/globals.php";

		ob_start();

		if(file_exists("custom/snippets/{$snippetName}.php")) {
			include "custom/snippets/{$snippetName}.php";
		} else {
			echo "Snippet \"{$snippetName}\" not found!";
		}

		return ob_get_clean();
	}

	private function parseSnippets() {
		$this->output = preg_replace_callback("/<y:snippet(.*)\/>/iU", array($this, 'getSnippets'), $this->output);
		$this->output = preg_replace_callback("/<y:snippet(.*)>.*<\/y:snippet>/smiU", array($this, 'getSnippets'), $this->output);
	}

	// PRIVATE: Parse php includes
	private function parsePHPIncludes() {
		$this->output = preg_replace_callback("/<y:php(.*)\/>/iU", array($this, 'getPHPIncludes'), $this->output);
		$this->output = preg_replace_callback("/<y:php(.*)>.*<\/y:php>/smiU", array($this, 'getPHPIncludes'), $this->output);
	}

	private function getPHPIncludes($phpMatches) {
		$phpInclude = simplexml_load_string($this->cleanCustomTag($phpMatches[0]));
		$phpIncludeFile = $phpInclude["src"];

		include "custom/globals.php";

		ob_start();

		if(file_exists("custom/{$phpIncludeFile}")) {
			$this->page->pageSettings["extension"] = "php";

			if($this->publisher !== false) {
				echo file_get_contents("custom/{$phpIncludeFile}");
			} else {
				include "custom/{$phpIncludeFile}";
			}
		} else {
			echo "PHP include \"{$phpIncludeFile}\" not found!";
		}

		return ob_get_clean();
	}

	// PRIVATE: Parse js files
	private function getJSFiles($jsMatches) {
		$jsXml = simplexml_load_string($this->cleanCustomTag($jsMatches[0]));
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

			$jsOutput = '<script src="' . $jsFrontendFile . '"' . ($jsAsync == true ? ' async="async"' : "" ) . '></script>';
		}

		return $jsOutput;
	}

	private function parseJSFiles() {
		$this->output = preg_replace_callback("/<y:minifyjs.*>.*<\/y:minifyjs>/smiU", array($this, 'getJSFiles'), $this->output);
	}

	// PRIVATE: Parse css files
	private function getCSSFiles($cssMatches) {
		$cssXml = simplexml_load_string($this->cleanCustomTag($cssMatches[0]));
		$cssMergedFile = (string)$cssXml["name"];
		$cssMedia = ((string)$cssXml["media"] == "") ? "screen" : (string)$cssXml["media"];

		$cssFiles = array();
		$cssOutput = "";

		foreach($cssXml->file as $sourceCssFile) {
			if(file_exists("custom/css/{$sourceCssFile}")) {
				if($this->publisher !== false) {
					$cssFiles[] = "custom/css/{$sourceCssFile}";
				} else {
					$cssOutput .= '<link rel="stylesheet" href="custom/css/' . $sourceCssFile . '?' . time() . '" media="' . $cssMedia . '" />';
				}
			}
		}

		if($this->publisher !== false) {
			$this->publisher->addCSSFiles($cssMergedFile, $cssFiles);

			$cssFrontendFolder = $this->yggdrasilConfig["frontend"]["mediaUrl"] . $this->yggdrasilConfig["frontend"]["cssFolder"];
			$cssFrontendFile = "{$cssFrontendFolder}/{$cssMergedFile}?CACHEBUSTER";

			$cssOutput = '<link rel="stylesheet" href="' . $cssFrontendFile . '" media="' . $cssMedia . '" />';
		}

		return $cssOutput;
	}

	private function parseCSSFiles() {
		$this->output = preg_replace_callback("/<y:minifycss.*>.*<\/y:minifycss>/smiU", array($this, 'getCSSFiles'), $this->output);
	}

	// PUBLIC: Show backend
	public function showBackend() {
		ob_start();

		include "custom/globals.php";
		include "core/backend/gui.php";

		$guiContent = ob_get_clean();

		$this->output = preg_replace("/(<\/body>.*<\/html>.*?)/smiU", "{$guiContent}$1", $this->output);
	}


	// PUBLIC: Init parse
	public function parse() {

		if($this->page->pageInfos != null) {
			if($this->page->redirect === null) {
				$this->parsePageSections();

				$this->parseTemplate();
				$this->parseSnippets();
				$this->parsePHPIncludes();
				$this->parseJSFiles();
				$this->parseCSSFiles();

			} else {
				if($this->publisher !== false) {
					$this->page->pageSettings["extension"] = "php";

					if(strpos($this->page->redirect["url"], "http") !== 0) {
						$this->page->redirect["url"] = $this->yggdrasilConfig["frontend"]["rootUrl"] . $this->page->redirect["url"];
					}

					if($this->page->redirect["type"] == 301) {
						$redirectString = "301 Moved Permanently";
					} else {
						$redirectString = "302 Moved Temporarly";
					}

					$this->output = '<?php header("HTTP/1.1 ' . $redirectString  . '"); header("Location: ' . $this->page->redirect["url"] . '"); exit; ?>';
				} else {
					$pageInfos = $this->page->pageInfos;
					$pageSettings = $this->page->pageSettings;
					$pageBaseUrl = $this->pageBaseUrl;

					ob_start();

					$infoTitle = "Redirect: {$this->page->redirect["url"]}";
					$infoContent = "This page redirects to: {$this->page->redirect["url"]} ({$this->page->redirect["type"]})";

					include "core/backend/info.php";

					$this->output = ob_get_clean();
				}
			}

			if($this->publisher !== false) {
				$this->publisher->addPage($this->page, $this->output);
				$this->publisher->addDependencies($this->page->pageSettings["dependencies"]);
			}
		} else {
			$this->output = "Site not found.";
		}
	}

	// PUBLIC: Get output
	public function getOutput() {
		return $this->output;
	}

}

?>