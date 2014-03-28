<!DOCTYPE html>

<html lang="de">
	<head>
		<title><?php echo $pageSettings["head"]["meta"]["title"] ?></title>
		<base href="<?php echo $pageBaseUrl ?>" />
		<link rel="canonical" href="/" />

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="msapplication-tap-highlight" content="no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

		<meta name="robots" content="<?php echo $pageSettings["head"]["meta"]["robots"] ?>" />
		<meta name="keywords" content="<?php echo $pageSettings["head"]["meta"]["keywords"] ?>" />
		<meta name="description" content="<?php echo $pageSettings["head"]["meta"]["description"] ?>" />

		<meta name="geo.region" content="<?php echo $pageSettings["head"]["geo"]["region"] ?>" />
		<meta name="geo.placename" content="<?php echo $pageSettings["head"]["geo"]["placename"] ?>" />
		<meta name="geo.position" content="<?php echo $pageSettings["head"]["geo"]["position"] ?>" />

		<?php foreach($pageSettings["head"]["og"]["image"] as $ogImage) { ?>
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
		<img src="<?php echo Image::src("content/dummy.gif") ?>" style="height:150px;width:350px;" />

		<?php if(isset($pageSections["content"])) { ?>
			<div id="content">
				<?php echo $pageSections["content"] ?>
			</div>
		<?php } ?>

		<?php if(isset($pageSections["sidebar"])) { ?>
			<div id="sidebar">
				<?php echo $pageSections["sidebar"] ?>
			</div>
		<?php } ?>

		<!-- Footer js -->
		<y:minifyjs name="merged.js" async="true">
			<y:file>jquery.min.js</y:file>
			<y:file>main.js</y:file>
		</y:minifyjs>
	</body>
</html>