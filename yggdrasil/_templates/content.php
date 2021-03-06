<!DOCTYPE html>

<html lang="de">
	<head>
		<title>{{pageSettings.head.meta.title}}</title>
		<base href="{{pageInfos.baseUrl}}" />
		<link rel="canonical" href="/" />

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="msapplication-tap-highlight" content="no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

		<meta name="robots" content="{{pageSettings.head.meta.robots}}" />
		<meta name="keywords" content="{{pageSettings.head.meta.keywords}}" />
		<meta name="description" content="{{pageSettings.head.meta.description}}" />

		<meta name="geo.region" content="{{pageSettings.head.geo.region}}" />
		<meta name="geo.placename" content="{{pageSettings.head.geo.placename}}" />
		<meta name="geo.position" content="{{pageSettings.head.geo.position}}" />

		<?php foreach($pageSettings["head"]["og"]["image"] as $ogImage) { ?>
			<meta property="og:image" content="<?php echo $ogImage ?>" />
		<?php } ?>

		<link rel="icon" href="<?php /*echo File::src("favicon.ico")*/ ?>" type="image/x-icon" />

		<!-- screen css -->
		<y:minifycss name="screen.css" media="screen">
			<y:file>css/screen.css</y:file>
			<y:file>css/screen2.css</y:file>
		</y:minifycss>

		<!-- print css -->
		<y:minifycss name="print.css" media="print">
			<y:file>css/print.css</y:file>
		</y:minifycss>
	</head>
	<body>
		<img src="<?php /*echo Image::src("content/dummy.gif")*/ ?>" style="height:150px;width:350px;" />

		<a href="<?php echo Link::href("site1/subsite1"); ?>">Testlink</a>

		<y:php>
			<?php $hiersovar = "da geht!" ?>
		</y:php>

		<!-- SECTION: Content -->
		<?php if(isset($pageSections["content"])) { ?>
			<div id="content">
				<y:renderSection name="content" />
			</div>
		<?php } ?>

		<!-- SECTION: Sidebar -->
		<?php if(isset($pageSections["sidebar"])) { ?>
			<div id="sidebar">
				<y:renderSection name="sidebar" />
			</div>
		<?php } ?>

		<y:php>
			<?php
				echo "x".$hiersovar."x";
			?>
		</y:php>

		<!-- Footer js -->
		<y:minifyjs name="merged.js" async="async">
			<y:file>js/jquery.min.js</y:file>
			<y:file>js/main.js</y:file>
		</y:minifyjs>
	</body>
</html>