<!DOCTYPE html>

<html lang="de">
<head>
	<title><?php echo "Yggdrasil " . $errorType ?></title>
	<base href="<?php echo YGGDRASIL_BACKEND_ROOT_URL ?>" />
	<?php include YGGDRASIL_BACKEND_CORE_DIR . "backend" . DS . "css.php"; ?>
</head>
<body>
	<h1><?php echo $errorType ?></h1>

	<p><?php echo $errorMessage ?></p>

	<p>File: <?php echo $errorFile ?></p>
	<p>Line: <?php echo $errorLine ?></p>

	<div id="yggdrasil-admin">
		<div class="controls">
			<a href="http://www.stephanlangenegger.ch" class="yggdrasil" target="_blank"></a>
		</div>
	</div>
</body>
</html>