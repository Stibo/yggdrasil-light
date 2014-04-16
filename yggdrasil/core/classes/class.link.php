<?php

class Link {

	// Auto detect link type
	static function href($link) {
		$returnLink = "";

		if(strpos($link, "http") === 0) {
			$returnLink = self::extern($link);
		} else {
			$returnLink = self::page($link);
		}

		return $returnLink;
	}

	// Page link
	static function page($path, $addHost = false) {
		global $yggdrasilConfig;

		$path = trim($path, "/ \t\n\r\0\x0B");
		$pageLink = "";

		if(PagePublisher::isEnabled()) {
			$pageLink = $path . "/";

			if($addHost) {
				$pageLink = $yggdrasilConfig["frontend"]["rootUrl"] . $pageLink;
			} else {
				$pageLink = "/" . $pageLink;
			}
		} else {
			$pageLink = $yggdrasilConfig["backend"]["rootUrl"] . "?pagePath=" . $path;
		}

		return $pageLink;
	}

	// Extern link
	static function extern($link) {
		return $link;
	}

}

?>