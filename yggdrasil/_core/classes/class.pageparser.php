<?php

class PageParser {

	// Private properties
	private $page;
	private $pageSections;
	private $output;

	// Constructor
	public function __construct($page) {
		$this->page = $page;

		$this->pageSections = array();
		$this->output = "";
	}

	// PRIVATE: Clean custom tag
	private function cleanCustomTag($customTag, $tagName) {
		return str_replace("<y:{$tagName}", "<y:{$tagName} xmlns:y=\"" . YGGDRASIL_BACKEND_TAG_NAMESPACE . "\"", $customTag);
	}

	// PRIVATE: Load page sections
	private function loadPageSections() {
		// Load page sections
		preg_match_all('/<y:' . YGGDRASIL_BACKEND_TAG_SECTION . '.*name=\"(.+)\".*>(.*)<\/y:' . YGGDRASIL_BACKEND_TAG_SECTION . '>/smiU', $this->page->loadContent(), $sectionMatches, PREG_SET_ORDER);

		foreach($sectionMatches as $sectionMatch) {
			$this->pageSections[$sectionMatch[1]] = trim($sectionMatch[2]);
		}
	}

	// PRIVATE: Load template
	private function loadTemplate() {
		// Load template file
		$templateFile = YGGDRASIL_BACKEND_TEMPLATE_DIR . $this->page->pageSettings["template"] . ".php";

		if(file_exists($templateFile)) {
			$this->output = file_get_contents($templateFile);
		}
	}

	// PRIVATE: Insert page section
	private function insertPageSection($match) {
		$sectionXML = simplexml_load_string($this->cleanCustomTag($match[0], YGGDRASIL_BACKEND_TAG_SECTION_RENDER));
		$sectionName = trim($sectionXML["name"]);

		if(isset($this->pageSections[$sectionName])) {
			$sectionContent = $this->pageSections[$sectionName];
		} else {
			$sectionContent = "";
		}

		return $sectionContent;
	}

	// PRIVATE: Parse page sections
	private function parsePageSections() {
		$this->output = preg_replace_callback("/<y:" . YGGDRASIL_BACKEND_TAG_SECTION_RENDER . "[^\/]*>(.*)<\/y:" . YGGDRASIL_BACKEND_TAG_SECTION_RENDER . ">/smiU", array($this, 'insertPageSection'), $this->output);
		$this->output = preg_replace_callback("/<y:" . YGGDRASIL_BACKEND_TAG_SECTION_RENDER . ".*\/>/iU", array($this, 'insertPageSection'), $this->output);
	}

	// PRIVATE: Parse recursive snippet nodes
	private function parseSnippetNode($node) {
		$nodeData = array();

		// Get attributes
		foreach($node->attributes() as $attrKey => $attrValue) {
			$nodeData["@{$attrKey}"] = trim((string)$attrValue);
		}

		// Get content
		$nodeData["_content"] = trim($node->saveXML());
		$nodeData["_content"] = preg_replace('/^<\?xml.*\?>/iU', "", $nodeData["_content"]);
		$nodeData["_content"] = preg_replace('/^<y:.+>(.*?)<\/y:.+>$/smiU', "$1", $nodeData["_content"]);
		$nodeData["_content"] = trim($nodeData["_content"]);

		// Get children
		foreach($node->children(YGGDRASIL_BACKEND_TAG_NAMESPACE) as $childName => $childNode) {
			if(array_search($childName, array(YGGDRASIL_BACKEND_TAG_PHPCODE, YGGDRASIL_BACKEND_TAG_SNIPPET, YGGDRASIL_BACKEND_TAG_SECTION_RENDER)) === false) {
				$nodeData[$childName][] = $this->parseSnippetNode($childNode);
			}
		}

		return $nodeData;
	}

	// PRIVATE: Insert snippet
	private function insertSnippet($match) {
		$snippetXML = simplexml_load_string($this->cleanCustomTag($match[0], YGGDRASIL_BACKEND_TAG_SNIPPET));
		$snippetName = trim($snippetXML["name"]);
		$snippetFile = YGGDRASIL_BACKEND_SNIPPET_DIR . $snippetName . ".php";

		if(file_exists($snippetFile)) {
			$snippetData = $this->parseSnippetNode($snippetXML);

			$snippetContent = '<?php $snippet = ' . var_export($snippetData, true) . '?>';
			$snippetContent .= file_get_contents($snippetFile);
			$snippetContent .= '<?php unset($snippet); ?>';
		} else {
			$snippetContent = "Snippet \"{$snippetName}\" not found!";
		}

		return $snippetContent;
	}

	// PRIVATE: Parse snippets
	private function parseSnippets() {
		$this->output = preg_replace_callback("/<y:" . YGGDRASIL_BACKEND_TAG_SNIPPET . "[^\/]*>(.*)<\/y:" . YGGDRASIL_BACKEND_TAG_SNIPPET . ">/smiU", array($this, 'insertSnippet'), $this->output);
		$this->output = preg_replace_callback("/<y:" . YGGDRASIL_BACKEND_TAG_SNIPPET . ".*\/>/iU", array($this, 'insertSnippet'), $this->output);
	}

	// PRIVATE: Insert php code
	private function insertPHPCode($match) {
		$phpXML = simplexml_load_string($this->cleanCustomTag($match[0], YGGDRASIL_BACKEND_TAG_PHPCODE));
		$phpCode = trim($match[1]);
		$phpContent = "";

		if($this->page->pageInfos["viewMode"] > 0) {
			$phpContent = $phpCode;
		} else {
			$phpContent = '<?php echo "' . addslashes($phpCode) . '"; ?>';
		}

		return $phpContent;
	}

	// PRIVATE: Parse php code
	private function parsePHPCode() {
		$this->output = preg_replace_callback("/<y:" . YGGDRASIL_BACKEND_TAG_PHPCODE . "[^\/]*>(.*)<\/y:" . YGGDRASIL_BACKEND_TAG_PHPCODE . ">/smiU", array($this, 'insertPHPCode'), $this->output);
		$this->output = preg_replace_callback("/<y:" . YGGDRASIL_BACKEND_TAG_PHPCODE . ".*\/>/iU", array($this, 'insertPHPCode'), $this->output);
	}

	// PRIVATE: Insert js files
	private function insertJSFiles($match) {
		$jsXML = simplexml_load_string($this->cleanCustomTag($match[0], YGGDRASIL_BACKEND_TAG_MINIFY_JS));
		$jsMergedName = trim($jsXML["name"]);
		$jsFileNodes = $jsXML->children(YGGDRASIL_BACKEND_TAG_NAMESPACE);

		$jsFiles = array();
		$jsOutput = "";

		foreach($jsFileNodes as $sourceJsFile) {
			if(file_exists(YGGDRASIL_BACKEND_ROOT_DIR . $sourceJsFile)) {
				if($this->page->pageInfos["viewMode"] <= 0) {
					$jsFiles[] = $sourceJsFile;
				} else {
					$jsOutput .= '<script src="' . $sourceJsFile . '?' . time() . '"></script>';
				}
			}
		}

		if($this->page->pageInfos["viewMode"] <= 0) {
			PagePublisher::addJSFiles($jsMergedName, $jsFiles);

			$jsMergedAttributes = (array)$jsXML->attributes();
			$jsMergedAttributes = $jsMergedAttributes["@attributes"];

			$scriptTagDom = new DOMDocument();
			$scriptTag = $scriptTagDom->appendChild(new DOMElement("script"));

			$scriptTag->setAttribute("src", YGGDRASIL_FRONTEND_MEDIA_URL . "{$jsMergedName}?CACHEBUSTER");

			foreach($jsMergedAttributes as $attrName => $attrValue) {
				if(array_search($attrName, array("name")) === false) {
					$scriptTag->setAttribute($attrName, $attrValue);
				}
			}

			$jsOutput = $scriptTagDom->saveHTML();

			// TODO: Überprüfen ob merged-File mit anderen subfiles bereits existiert
		}

		return $jsOutput;
	}

	// PRIVATE: Parse js files
	private function parseJSFiles() {
		$this->output = preg_replace_callback("/<y:" . YGGDRASIL_BACKEND_TAG_MINIFY_JS . "[^\/]*>(.*)<\/y:" . YGGDRASIL_BACKEND_TAG_MINIFY_JS . ">/smiU", array($this, 'insertJSFiles'), $this->output);
	}

	// PRIVATE: Insert css files
	private function insertCSSFiles($match) {
		$cssXML = simplexml_load_string($this->cleanCustomTag($match[0], YGGDRASIL_BACKEND_TAG_MINIFY_CSS));
		$cssMergedName = trim($cssXML["name"]);
		$cssFileNodes = $cssXML->children(YGGDRASIL_BACKEND_TAG_NAMESPACE);
		$cssMedia = ((string)$cssXML["media"] == "") ? "screen" : (string)$cssXML["media"];

		$cssFiles = array();
		$cssOutput = "";

		foreach($cssFileNodes as $sourceCssFile) {
			if(file_exists(YGGDRASIL_BACKEND_ROOT_DIR . $sourceCssFile)) {
				if($this->page->pageInfos["viewMode"] <= 0) {
					$cssFiles[] = $sourceCssFile;
				} else {
					$cssOutput .= '<link rel="stylesheet" href="' . $sourceCssFile . '?' . time() . '" media="' . $cssMedia . '" />';
				}
			}
		}

		if($this->page->pageInfos["viewMode"] <= 0) {
			PagePublisher::addCssFiles($cssMergedName, $cssFiles);

			$cssMergedAttributes = (array)$cssXML->attributes();
			$cssMergedAttributes = $cssMergedAttributes["@attributes"];

			$styleTagDom = new DOMDocument();
			$styleTag = $styleTagDom->appendChild(new DOMElement("link"));

			$styleTag->setAttribute("rel", "stylesheet");
			$styleTag->setAttribute("href", YGGDRASIL_FRONTEND_MEDIA_URL . "{$cssMergedName}?CACHEBUSTER");

			foreach($cssMergedAttributes as $attrName => $attrValue) {
				if(array_search($attrName, array("name", "rel")) === false) {
					$styleTag->setAttribute($attrName, $attrValue);
				}
			}

			$cssOutput = $styleTagDom->saveHTML();

			// TODO: Überprüfen ob merged-File mit anderen subfiles bereits existiert
		}

		return $cssOutput;
	}

	// PRIVATE: Parse css files
	private function parseCSSFiles() {
		$this->output = preg_replace_callback("/<y:" . YGGDRASIL_BACKEND_TAG_MINIFY_CSS . "[^\/]*>(.*)<\/y:" . YGGDRASIL_BACKEND_TAG_MINIFY_CSS . ">/smiU", array($this, 'insertCSSFiles'), $this->output);
	}

	// PRIVATE: Prepend settings
	private function prependSettings() {
		// Add constants
		$settingsConstants = get_defined_constants(true);
		$settingsConstants = $settingsConstants["user"];

		$settings = "<?php ";

		foreach($settingsConstants as $constName => $constValue) {
			$settings .= 'define("' . $constName . '", ' . var_export(constant($constName), true) . ');' . PHP_EOL;
		}

		$settings .= "?>";

		// Add page settings
		$settings .= '<?php $pageSettings = ' . var_export($this->page->pageSettings, true) . '; ?>';

		// Add page infos
		$settings .= '<?php $pageInfos = ' . var_export($this->page->pageInfos, true) . '; ?>';

		// Add globals
		if(file_exists(YGGDRASIL_BACKEND_CONFIG_GLOBALS_FILE)) {
			$settings .= file_get_contents(YGGDRASIL_BACKEND_CONFIG_GLOBALS_FILE);
		}

		$this->output = $settings . $this->output;
	}

	// PUBLIC: Create compiled file
	public function createCompiledFile() {
		if(!file_exists(YGGDRASIL_BACKEND_TEMP_COMPILER_DIR)) {
			mkdir(YGGDRASIL_BACKEND_TEMP_COMPILER_DIR);
		}

		file_put_contents(YGGDRASIL_BACKEND_TEMP_COMPILER_DIR . $this->page->pageInfos["compiledFile"], $this->getOutput());
	}

	// PUBLIC: Get compiled file
	public function getCompiledFile() {
		ob_start();

		include YGGDRASIL_BACKEND_TEMP_COMPILER_DIR . $this->page->pageInfos["compiledFile"];

		$compiledFileContent = ob_get_clean();

		return $compiledFileContent;
	}

	// PUBLIC: Show backend
	public function showBackend() {
		$guiContent = file_get_contents(YGGDRASIL_BACKEND_CORE_DIR . "backend" . DS . "gui.php");

		$this->output = preg_replace("/(<\/body>.*<\/html>.*?)/smiU", "{$guiContent}$1", $this->output);
	}

	// PUBLIC: Init parse
	public function parse() {
		$this->loadPageSections();
		$this->loadTemplate();

		$this->parsePageSections();
		$this->parseSnippets();
		$this->parsePHPCode();

		$this->parseJSFiles();
		$this->parseCSSFiles();

		$this->prependSettings();
	}

	// PUBLIC: Set output
	public function setOutput($output) {
		$this->output = $output;
	}

	// PUBLIC: Get output
	public function getOutput() {
		return $this->output;
	}

}

?>