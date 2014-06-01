<?php

class PagePublisher {

	// Private properties
	private static $enabled = false;

	// STATIC: Check if publisher is enabled
	public static function isEnabled() {
		return self::$enabled;
	}

}

?>