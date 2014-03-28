<!DOCTYPE html>

<html lang="de">
	<head>
		<title>Redirect: <?php echo $this->page->redirect["url"] ?></title>
		<base href="<?php echo $pageBaseUrl ?>" />

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="msapplication-tap-highlight" content="no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

		<link rel="icon" href="/favicon.ico" type="image/x-icon" />
	</head>

	<body>
		This page redirects to: <?php echo $this->page->redirect["url"] ?> (<?php echo $this->page->redirect["type"] ?>)
	</body>
</html>