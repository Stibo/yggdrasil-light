<?php

class Page {

	// Public properties
	public $redirect;

	// PRIVATE: Set redirect
	private function setRedirect($redirect) {
		if(!isset($redirect["type"])) {
			$redirect["type"] = 301;
		}

		$this->redirect = $redirect;
	}

	// PUBLIC: Get subpages
	public function getSubPages($showInactive = false) {
		$pagesList = array();

		$pageIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->yggdrasilConfig["backend"]["pageDir"]));

		while($pageIterator->valid()) {
			if(!$pageIterator->isDot()) {
				$pagePath = str_replace("\\", "/", $pageIterator->getSubPath());
				$pageName = array_slice(explode("/", $pagePath), -1);
				$pageName = $pageName[0];

				$pageIsInactive = substr($pageName, 0, 1) == "_";

				if((($this->pageInfos["path"] != "" && strpos($pagePath, $this->pageInfos["path"]) === 0) || $this->pageInfos["path"] == "") && (!$pageIsInactive || $showInactive == true)) {
					$pagesList[] = $pagePath;
				}
			}

			$pageIterator->next();
		}

		sort($pagesList);

		return $pagesList;
	}
}

?>