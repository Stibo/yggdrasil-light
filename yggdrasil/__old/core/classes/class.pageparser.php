<?php

class PageParser {

	// PUBLIC: Init parse
	public function parse() {

		if($this->page->pageInfos != null) {
			if($this->page->redirect === null) {

			} else {
				if(PagePublisher::isEnabled()) {
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

					ob_start();

					$infoTitle = "Redirect: {$this->page->redirect["url"]}";

					if(strpos($this->page->redirect["url"], "http") !== 0) {
						$infoContent = "This page redirects to: <a href=\"{$this->yggdrasilConfig["backend"]["rootUrl"]}?pagePath={$this->page->redirect["url"]}\">{$this->page->redirect["url"]}</a> ({$this->page->redirect["type"]})";
					} else {
						$infoContent = "This page redirects to: {$this->page->redirect["url"]} ({$this->page->redirect["type"]})";
					}

					include "core/backend/info.php";

					$this->output = ob_get_clean();
				}
			}

			if(PagePublisher::isEnabled()) {
				PagePublisher::addPage($this->page, $this->output);
				PagePublisher::addDependencies($this->page->pageSettings["dependencies"]);
			}
		}
	}

}

?>