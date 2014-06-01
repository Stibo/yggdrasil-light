<?php

class File {

	static $publishedFiles = array();

	static function src($file) {
		global $yggdrasilConfig;

		$fileLink = "";

		if(PagePublisher::isEnabled()) {
			$fileTargetPath = $yggdrasilConfig["backend"]["tempDir"] . __DS__ . str_replace("/", __DS__, $file);
			$fileTargetDir = dirname($fileTargetPath);
			$fileSourcePath = $yggdrasilConfig["backend"]["customDir"] . str_replace("/", __DS__, $file);

			// Create subfolders if not exists
			if(!file_exists($fileTargetDir)) {
				mkdir($fileTargetDir, 0755, true);
			}

			// Compress the image if not already exists
			if(array_search($fileSourcePath, self::$publishedFiles) === false) {
				self::$publishedFiles[] = $fileSourcePath;

				$newFileSource = $yggdrasilConfig["backend"]["customDir"] . str_replace("/", __DS__, $file);
				$newFileTarget = $fileTargetPath;

				copy($newFileSource, $newFileTarget);
			}
		}

		return $file;
	}

}

?>