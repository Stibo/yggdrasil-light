<?php

// Init core
require_once "init.php";

set_time_limit(YGGDRASIL_BACKEND_ACTION_TIMEOUT);

// Get action
$actionName = isset($_GET["action"]) ? $_GET["action"] : "";

switch($actionName) {
	// Action not found
	default:
		echo "Action not found";

	break;
}

?>