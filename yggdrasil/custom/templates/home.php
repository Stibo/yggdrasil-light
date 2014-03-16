<!DOCTYPE html>

<html lang="de">
	<head>
		<title><?php echo $this->page->config["head"]["meta"]["title"] ?></title>
		<!-- TODO: SET FRONTEND AND BACKEND BASE URL -->
		<base href="<?php echo $this->yggdrasilConfig["backend"]["rootUrl"] ?>/" />
		<link rel="canonical" href="/" />

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="msapplication-tap-highlight" content="no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

		<meta name="robots" content="<?php echo $this->page->config["head"]["meta"]["robots"] ?>" />
		<meta name="keywords" content="<?php echo $this->page->config["head"]["meta"]["keywords"] ?>" />
		<meta name="description" content="<?php echo $this->page->config["head"]["meta"]["description"] ?>" />

		<meta name="geo.region" content="<?php echo $this->page->config["head"]["geo"]["region"] ?>" />
		<meta name="geo.placename" content="<?php echo $this->page->config["head"]["geo"]["placename"] ?>" />
		<meta name="geo.position" content="<?php echo $this->page->config["head"]["geo"]["position"] ?>" />

		<?php foreach($this->page->config["head"]["og"]["image"] as $ogImage) { ?>
			<meta property="og:image" content="<?php echo $ogImage ?>" />
		<?php } ?>

		<link rel="icon" href="/favicon.ico" type="image/x-icon" />

		<!-- screen css -->
		<y:minifycss name="screen.css" media="screen">
			<y:file>screen.css</y:file>
			<y:file>screen2.css</y:file>
		</y:minifycss>

		<!-- print css -->
		<y:minifycss name="print.css" media="print">
			<y:file>print.css</y:file>
		</y:minifycss>
	</head>

	<body>
		<?php /*dump($this->page->config);*/ ?>

		<div id="content">
			<?php echo $this->pageSections["content"] ?>
		</div>

		<div id="sidebar">
			<?php echo $this->pageSections["sidebar"] ?>
		</div>

		<!-- Footer js -->
		<y:minifyjs name="merged.js" async="true">
			<y:file>jquery.min.js</y:file>
			<y:file>main.js</y:file>
		</y:minifyjs>
	</body>
</html>