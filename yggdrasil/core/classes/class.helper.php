<?php

class Helper {

	// Copy folder recursive
	static function copy_recurse($source, $destination) {
		$directory = opendir($source);

		@mkdir($destination);

		while(false !== ($file = readdir($directory))) {
			if(($file != '.') && ($file != '..')) {
				if(is_dir($source . __DS__ . $file)) {
					Helper::copy_recurse($source . __DS__ . $file, $destination . __DS__ . $file);
				} else {
					copy($source . __DS__ . $file,$destination . __DS__ . $file);
				}
			}
		}

		closedir($directory);
	}

	// Delete folder recursive
	static function delete_recurse($path, $removeFolder, $dontIgnoreFolders = false) {
		global $yggdrasilConfig;

		$directory = new DirectoryIterator($path);

		foreach($directory as $fileinfo) {
			if(!$fileinfo->isDot()) {

				$ignoreDir = array_search($fileinfo->getPathName(), $yggdrasilConfig["frontend"]["ignoreDirs"]) !== false;

				if((!$dontIgnoreFolders && !$ignoreDir) || $dontIgnoreFolders) {
					if($fileinfo->isFile()) {
						unlink($fileinfo->getPathName());
					} else {
						Helper::delete_recurse($fileinfo->getPathName(), true, $dontIgnoreFolders);
					}
				}
			}
		}

		if($removeFolder) {
			rmdir($path);
		}
	}

}

?>