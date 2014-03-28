<?php

class Image {

	static function src($image) {
		global $publishMode, $yggdrasilConfig;

		$imageLink = "";

		if($publishMode) {
			$smushitUrl = "http://www.smushit.com/ysmush.it/ws.php";

			$imageTargetPath = $yggdrasilConfig["backend"]["tempDir"] . "img" . __DS__ . str_replace("/", __DS__, $image);
			$imageTargetDir = dirname($imageTargetPath);
			$imageSourcePath = $yggdrasilConfig["backend"]["imagesDir"] . str_replace("/", __DS__, $image);

			// Create subfolders if not exists
			if(!file_exists($imageTargetDir)) {
				mkdir($imageTargetDir, 755, true);
			}

			// Compress the image if not already exists
			if(!file_exists($imageTargetPath)) {
				$postFields = array("output" => "json");
				$postContent = "";
				$postBoundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);

				//Collect Postdata
				foreach($postFields as $fieldKey => $fielValue) {
					$postContent .= "--$postBoundary\n";
					$postContent .= "Content-Disposition: form-data; name=\"{$fieldKey}\"\n\n{$fielValue}\n";
				}

				$postContent .= "--$postBoundary\n";

				// Fileupload
				$fileContents = file_get_contents($imageSourcePath);

				$postContent .= "Content-Disposition: form-data; name=\"files\"; filename=\"".basename($imageSourcePath)."\"\n";
				$postContent .= "Content-Type: image/jpeg\n";
				$postContent .= "Content-Transfer-Encoding: binary\n\n";
				$postContent .= "{$fileContents}\n";
				$postContent .= "--{$postBoundary}--\n";

				$streamParams = array("http" => array(
					"method" => "POST",
					"header" => "Content-Type: multipart/form-data; boundary={$postBoundary}",
					"content" => $postContent
				));

				$streamContext = stream_context_create($streamParams);
				$streamFile = fopen($smushitUrl, "rb", false, $streamContext);

				if(!$streamFile) {
					throw new Exception("Problem with $smushitUrl, $php_errormsg");
				}

				$streamResponse = @stream_get_contents($streamFile);

				if($streamResponse === false) {
					throw new Exception("Problem reading data from {$smushitUrl}, {$php_errormsg}");
				}

				$decodedResponse = json_decode($streamResponse, true);

				if(is_null($decodedResponse)) {
					$newFileSource = $yggdrasilConfig["backend"]["imagesDir"] . str_replace("/", __DS__, $image);
					$newFileTarget = $imageTargetPath;
					$newImageUrl = $image;
				} else {
					$newFileSource = $decodedResponse["dest"];
					$newFileTarget = $imageTargetDir . __DS__ . array_shift(explode(".", basename($image))) . "." . pathinfo($decodedResponse["dest"], PATHINFO_EXTENSION);
					$newImageUrl = array_shift(explode(".", $image)) . "." . pathinfo($decodedResponse["dest"], PATHINFO_EXTENSION);
				}

				copy($newFileSource, $newFileTarget);
			} else {
				$newImageUrl = $image;
			}

			$imageLink = "img/" . $newImageUrl;
		} else {
			$imageLink = "custom/img/" . $image;
		}

		return $imageLink;
	}

}

?>