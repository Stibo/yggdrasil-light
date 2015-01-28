<?php

class Page {

	// Public properties
	public $pageSettings;
	public $pageInfos;

	// Constructor
	public function __construct($pagePath) {
		global $defaultPageSettings;

		// Set viewmode if not defined
		if(!defined("YGGDRASIL_VIEWMODE")) {
			define("YGGDRASIL_VIEWMODE", -10);
		}

		// Get default page settings
		$this->pageSettings = $defaultPageSettings;

		// Get page path and name
		$this->pageInfos["path"] = preg_replace("/[^a-z0-9\-_\/]/i", "", trim(trim($pagePath), "/"));
		$explodedPath = explode("/", $this->pageInfos["path"]);
		$this->pageInfos["name"] = array_pop($explodedPath);

		// Get page path
		$this->pageInfos["backendDir"] = YGGDRASIL_BACKEND_PAGE_DIR . str_replace("/", DS, $this->pageInfos["path"]) . DS;

		// Get page settings file
		$pageSettingsFile = glob($this->pageInfos["backendDir"] . "*" . YGGDRASIL_BACKEND_PAGE_SETTINGS_FILE);
		$this->pageInfos["backendSettingsFile"] = @array_shift($pageSettingsFile);

		// Get page content file
		$pageContentFile = glob($this->pageInfos["backendDir"] . YGGDRASIL_BACKEND_PAGE_CONTENT_FILE);
		$this->pageInfos["backendContentFile"] = @array_shift($pageContentFile);

		// Get full path to the frontend page
		$this->pageInfos["frontendDir"] = YGGDRASIL_FRONTEND_ROOT_DIR . str_replace("/", DS, $this->pageInfos["path"]) . DS;
		$indexFile = glob("{$this->pageInfos["frontendDir"]}index.*"); // TODO: Verschiedene url types
		$this->pageInfos["frontendFile"] = array_shift($indexFile);

		// Set base url
		$this->pageInfos["baseUrl"] = PagePublisher::isEnabled() ? YGGDRASIL_FRONTEND_ROOT_URL : YGGDRASIL_BACKEND_ROOT_URL;

		// Set active
		$this->pageInfos["isActive"] = !(substr($this->pageInfos["backendSettingsFile"], -strlen(YGGDRASIL_BACKEND_PAGE_SETTINGS_FILE) - 1, 1) == "_");

		// Set publish date
		$this->pageInfos["publishDate"] = -1;
		$this->pageInfos["publishDateFormatted"] = -1;

		if(!is_null($this->pageInfos["frontendFile"]) && file_exists($this->pageInfos["frontendFile"])) {
			$this->pageInfos["publishDate"] = filemtime($this->pageInfos["frontendFile"]);

			if($format) {
				$this->pageInfos["publishDateFormatted"] = date(YGGDRASIL_BACKEND_DATETIME_FORMAT, $publishDate);
			}
		}

		// Set compiled filename
		$this->pageInfos["compiledFile"] = str_replace("/", "_", $this->pageInfos["path"]) . "." . YGGDRASIL_VIEWMODE . ".php";

		// Load settings if page exists
		if($this->exists()) {
			$this->loadSettings();
		} else {
			$this->pageSettings = null;
		}
	}

	// PUBLIC: Check if page exists
	public function exists() {
		return !is_null($this->pageInfos["backendSettingsFile"]);
	}

	// PUBLIC: Load page settings
	public function loadSettings() {
		global $defaultPageSettings;

		$pageSettings = &$this->pageSettings;
		$pageInfos = $this->pageInfos;

		if(file_exists(YGGDRASIL_BACKEND_CONFIG_GLOBALS_FILE)) {
			require YGGDRASIL_BACKEND_CONFIG_GLOBALS_FILE;
		}

		require $this->pageInfos["backendSettingsFile"];
	}

	// PUBLIC: Load content
	public function loadContent() {
		$content = "";

		if(!is_null($this->pageInfos["backendContentFile"])) {
			$content = file_get_contents($this->pageInfos["backendContentFile"]);
		}

		return $content;
	}

	// PUBLIC: Toggle page
	public function toggle() {
		$newPagePath = "";

		if($this->pageInfos["isActive"]) {
			$newPagePath = $this->disable();
		} else {
			$newPagePath = $this->enable();
		}

		return $newPagePath;
	}

	// PUBLIC: Enable page
	public function enable() {
		// Rename backend file
		$sourceFile = $this->pageInfos["backendSettingsFile"];
		$targetFile = $this->pageInfos["backendDir"] . YGGDRASIL_BACKEND_PAGE_SETTINGS_FILE;

		@rename($sourceFile, $targetFile);

		return $targetFile;
	}

	// PUBLIC: Disable page
	public function disable() {
		// Rename backend file
		$sourceFile = $this->pageInfos["backendSettingsFile"];
		$targetFile = $this->pageInfos["backendDir"] . "_" . YGGDRASIL_BACKEND_PAGE_SETTINGS_FILE;

		@rename($sourceFile, $targetFile);

		// Remove folder from frontend if exists
		if(file_exists($this->pageInfos["frontendDir"])) {
			Helper::delete_recurse($this->pageInfos["frontendDir"], true);
		}

		return $targetFile;
	}
}

?>