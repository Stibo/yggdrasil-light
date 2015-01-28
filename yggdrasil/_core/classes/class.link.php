<?php

class Link {

	// Auto detect link type
	static function href($link, $addHost = YGGDRASIL_LINK_ADDHOST) {
		if(strpos($link, "http") === 0) {
			$returnLink = self::extern($link);
		} else {
			$returnLink = self::page($link, $addHost);
		}

		return $returnLink;
	}

	// Page link
	static function page($path, $addHost = YGGDRASIL_LINK_ADDHOST) {
		$pageLink = "";
		$path = trim(trim($path), "/");

		if($path != "") {
			$path = $path . "/";

			if(YGGDRASIL_VIEWMODE > 0) {
				$pageLink = YGGDRASIL_BACKEND_ROOT_URL . $path;
			} else {
				$pageLink = $path;

				if($addHost) {
					$pageLink = YGGDRASIL_FRONTEND_ROOT_URL . $pageLink;
				} else {
					$pageLink = "/" . $pageLink;
				}
			}

			if($pageLink == "//") {
				$pageLink = "/";
			}

		} else {
			$pageLink = "";
		}

		return $pageLink;
	}

	// Extern link
	static function extern($link) {
		return $link;
	}

}

?>