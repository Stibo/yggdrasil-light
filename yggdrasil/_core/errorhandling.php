<?php

function log_error($num, $str, $file, $line, $context = null) {
	log_exception(new ErrorException($str, 0, $num, $file, $line));
}


function log_exception(Exception $exception) {
	ob_get_clean();

	$errorType = get_class($exception);
	$errorMessage = $exception->getMessage();
	$errorFile = $exception->getFile();
	$errorLine = $exception->getLine();

	include YGGDRASIL_BACKEND_CORE_DIR . "backend" . DS . "error.php";

	exit();
}

function check_for_fatal() {
	$error = error_get_last();

	if($error["type"] == E_ERROR) {
		log_error($error["type"], $error["message"], $error["file"], $error["line"]);
	}
}

register_shutdown_function("check_for_fatal");
set_error_handler("log_error");
set_exception_handler("log_exception");
ini_set("display_errors", "off");
error_reporting(E_ALL);

?>