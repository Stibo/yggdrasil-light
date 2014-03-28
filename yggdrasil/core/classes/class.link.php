<?php

class Link {

	static function page($path) {
		global $publishMode, $yggdrasilConfig;

		$pageLink = "";

		if($publishMode) {
			$pageLink = $path;

			if(substr($pageLink, -1) !== "/") {
				$pageLink .= "/";
			}
		} else {
			$pageLink = $yggdrasilConfig["backend"]["rootUrl"] . "?pagePath=" . $path;
		}

		return $pageLink;
	}

}

?>