<?php define("DS", '/');
define("YGGDRASIL_ENVIRONMENT", 'prod');
define("YGGDRASIL_FRONTEND_ROOT_URL", 'http://yggdrasil.stibo.ch/');
define("YGGDRASIL_FRONTEND_ROOT_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/www/');
define("YGGDRASIL_VERSION", '0.7.0');
define("YGGDRASIL_FRONTEND_MEDIA_URL", 'http://yggdrasil.stibo.ch/');
define("YGGDRASIL_FRONTEND_URL_TYPE", 'folder');
define("YGGDRASIL_BACKEND_ROOT_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/');
define("YGGDRASIL_BACKEND_ROOT_URL", '//yggdrasil.stibo.ch/yggdrasil/');
define("YGGDRASIL_BACKEND_CORE_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_core/');
define("YGGDRASIL_BACKEND_TEMP_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_temp/');
define("YGGDRASIL_BACKEND_TEMP_COMPILER_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_temp/compiler/');
define("YGGDRASIL_BACKEND_TEMP_PUBLISHER_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_temp/publisher/');
define("YGGDRASIL_BACKEND_TEMP_PUBLISHER_LOCKFILE", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_temp/publisher-lock.json');
define("YGGDRASIL_BACKEND_PAGE_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_pages/');
define("YGGDRASIL_BACKEND_CONFIG_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_config/');
define("YGGDRASIL_BACKEND_CONFIG_GLOBALS_FILE", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_config/globals.php');
define("YGGDRASIL_BACKEND_PAGE_CONTENT_FILE", 'content.php');
define("YGGDRASIL_BACKEND_PAGE_SETTINGS_FILE", 'page.php');
define("YGGDRASIL_BACKEND_TEMPLATE_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_templates/');
define("YGGDRASIL_BACKEND_SNIPPET_DIR", '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_snippets/');
define("YGGDRASIL_BACKEND_DATETIME_FORMAT", 'd.m.Y H:i');
define("YGGDRASIL_BACKEND_ACTION_TIMEOUT", 1200);
define("YGGDRASIL_BACKEND_MENU_POSITION", 'right');
define("YGGDRASIL_BACKEND_TAG_NAMESPACE", 'yggdrasil-namespace');
define("YGGDRASIL_BACKEND_TAG_SECTION", 'section');
define("YGGDRASIL_BACKEND_TAG_SECTION_RENDER", 'renderSection');
define("YGGDRASIL_BACKEND_TAG_SNIPPET", 'snippet');
define("YGGDRASIL_BACKEND_TAG_PHPCODE", 'php');
define("YGGDRASIL_BACKEND_TAG_MINIFY_JS", 'minifyjs');
define("YGGDRASIL_BACKEND_TAG_MINIFY_CSS", 'minifycss');
?><?php $pageSettings = array (
  'template' => 'content',
  'extension' => 'html',
  'head' => 
  array (
    'meta' => 
    array (
      'title' => 'FUCKING TITLE',
      'description' => 'Default description',
      'keywords' => 'Default keywords',
      'robots' => 'Default robots',
    ),
    'geo' => 
    array (
      'region' => 'CH-LU',
      'placename' => 'Luzern',
      'position' => '47.049407;8.325877',
    ),
    'og' => 
    array (
      'type' => 'Default og type',
      'site_name' => 'Default og sitename',
      'title' => 'Default og title',
      'description' => 'Default og description',
      'url' => 'Default og url',
      'image' => 
      array (
        0 => 'Default og image 1',
        1 => 'Default og image 2',
        2 => 'Default og image 3',
      ),
    ),
  ),
  'dependencies' => 
  array (
    'favicon.ico' => 'files/favicon.ico',
    'robots.txt' => 'files/robots_live.txt',
    '.htaccess' => 'files/.htaccess',
  ),
); ?><?php $pageInfos = array (
  'path' => 'site1/subsite1',
  'name' => 'subsite1',
  'backendDir' => '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_pages/site1/subsite1/',
  'backendSettingsFile' => '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_pages/site1/subsite1/page.php',
  'backendContentFile' => '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/_pages/site1/subsite1/content.php',
  'frontendDir' => '/home/stibepr/www/yggdrasil.stibo.ch/www/site1/subsite1/',
  'frontendFile' => NULL,
  'baseUrl' => '/home/stibepr/www/yggdrasil.stibo.ch/yggdrasil/',
  'viewMode' => 10,
  'isActive' => true,
  'publishDate' => -1,
  'publishDateFormatted' => -1,
  'compiledFile' => 'site1_subsite1.php',
); ?><?php

$globals = array(

);

?><!DOCTYPE html>

<html lang="de">
	<head>
		<title><?php echo $pageSettings["head"]["meta"]["title"] ?></title>
		<title>{{pageSettings->head->meta->title}}</title>
		<base href="<?php echo $pageInfos["baseUrl"] ?>" />
		<base href="{{pageInfos->baseUrl}}" />
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
		<link rel="stylesheet" href="css/screen.css?1401630420" media="screen" /><link rel="stylesheet" href="css/screen2.css?1401630420" media="screen" />

		<!-- print css -->
		<link rel="stylesheet" href="css/print.css?1401630420" media="print" />
	</head>

	<body>
		<img src="<?php echo Image::src("content/dummy.gif") ?>" style="height:150px;width:350px;" />

		<!-- SECTION: Content -->
		<?php if(isset($pageSections["content"])) { ?>
			<div id="content">
				<?php $snippet = array (
  '@name' => 'linklist',
  '@class' => 'tile bold',
  '_content' => '<y:title>Die Highlights der Route</y:title>
		<y:subtitle>Kroatische Top Spots</y:subtitle>
		<y:list>
			<y:listitem class="green">Split</y:listitem>
			<y:listitem>Carpe Diem Beach</y:listitem>
			<y:listitem>Hvar</y:listitem>
		</y:list>
		<y:mofo>

			<?php echo "static: " . time(); ?>

			<div class="yodynshit">
					<?php echo "dyna\'mic: " . time(); ?>
				</div>

		</y:mofo>',
  'title' => 
  array (
    0 => 
    array (
      '_content' => 'Die Highlights der Route',
    ),
  ),
  'subtitle' => 
  array (
    0 => 
    array (
      '_content' => 'Kroatische Top Spots',
    ),
  ),
  'list' => 
  array (
    0 => 
    array (
      '_content' => '<y:listitem class="green">Split</y:listitem>
			<y:listitem>Carpe Diem Beach</y:listitem>
			<y:listitem>Hvar</y:listitem>',
      'listitem' => 
      array (
        0 => 
        array (
          '@class' => 'green',
          '_content' => 'Split',
        ),
        1 => 
        array (
          '_content' => 'Carpe Diem Beach',
        ),
        2 => 
        array (
          '_content' => 'Hvar',
        ),
      ),
    ),
  ),
  'mofo' => 
  array (
    0 => 
    array (
      '_content' => '<?php echo "static: " . time(); ?>

			<div class="yodynshit">
					<?php echo "dyna\'mic: " . time(); ?>
				</div>',
    ),
  ),
)?><ul>
	<?php foreach($snippet->link as $link) { ?>
		<li>
			<a href="<?php echo $link["href"] ?>"><?php echo $link ?></a>
		</li>
	<?php } ?>
</ul><?php unset($snippet); ?>


	<?php $snippet = array (
  '@name' => 'linklist',
  '_content' => '<span class="mofoarrow"/>
		asopdsaopdok<strong>psaopdopska</strong>dopksadsad
		<saopfksaopfosaf/>
		<opsafksaf/>',
)?><ul>
	<?php foreach($snippet->link as $link) { ?>
		<li>
			<a href="<?php echo $link["href"] ?>"><?php echo $link ?></a>
		</li>
	<?php } ?>
</ul><?php unset($snippet); ?>
			</div>
		<?php } ?>

		<!-- SECTION: Sidebar -->
		<?php if(isset($pageSections["sidebar"])) { ?>
			<div id="sidebar">
				
			</div>
		<?php } ?>

		<?php
				echo time();
			?>

		<!-- Footer js -->
		<script src="js/jquery.min.js?1401630420"></script><script src="js/main.js?1401630420"></script>
	<style type="text/css">
	#yggdrasil-admin {
		position: fixed;
		z-index: 1000000;
		bottom: 0;
		right: 0;
		font-size: 0;
		line-height: 0;
		opacity: 0.4;
		transition: opacity .15s ease;
	}

	#yggdrasil-admin:hover {
		opacity: 1;
	}

	#yggdrasil-admin div.controls {
		margin-top: 2px;
		text-align: right;
	}

	#yggdrasil-admin a,
	#yggdrasil-admin .lastpublished {
		display: inline-block;
		vertical-align: top;
		height: 60px;
		width: 60px;
		margin-right: 2px;
		background-color: rgba(0,0,0,.85);
		font-size: 12px;
		line-height: 60px;
		color: white;
		text-align: center;
		background-position: center center;
		background-repeat: no-repeat;
		box-sizing: border-box;
	}

	#yggdrasil-admin a:hover {
		background-color: rgba(0,0,0,1);
	}

	#yggdrasil-admin .lastpublished {
		font-family: Arial;
		font-size: 13px;
		line-height: 1.5;
		padding-top: 13px;
		width: 122px;
	}

	#yggdrasil-admin .lastpublished span {
		display: block;
		font-size: 11px;
		color: gray;
	}

	#yggdrasil-admin a.warning {
		background-image: url('../core/backend/img/icon-warning.png');
	}

	#yggdrasil-admin a.error {
		background-image: url('../core/backend/img/icon-error.png');
	}

	#yggdrasil-admin a.menu {
		background-image: url('../core/backend/img/icon-menu.png');
	}

	#yggdrasil-admin a.toggle.active {
		background-image: url('../core/backend/img/icon-togglepage-active.png');
	}

	#yggdrasil-admin a.toggle.inactive {
		background-image: url('../core/backend/img/icon-togglepage-inactive.png');
	}

	#yggdrasil-admin a.toggle.inactive {
		background-image: url('../core/backend/img/icon-togglepage-inactive.png');
	}

	#yggdrasil-admin a.publish.page {
		background-image: url('../core/backend/img/icon-publish-page.png');
	}

	#yggdrasil-admin a.publish.subpages {
		background-image: url('../core/backend/img/icon-publish-subpages.png');
	}

	#yggdrasil-admin a.publish.all {
		background-image: url('../core/backend/img/icon-publish-all.png');
	}

	#yggdrasil-admin a.yggdrasil {
		background-image: url('../core/backend/img/icon-yggdrasil.png');
	}
</style>

<div id="yggdrasil-admin">
	<!--<div class="controls">
		<a href="" class="warning"></a>
	</div>-->

	<div class="controls">
		<div class="lastpublished">
			<span>Last published:</span>
			<?php

				$publishDate = $pageInfos["publishDateFormatted"];

				echo ($publishDate != -1) ? $publishDate : "Not yet published";

			?>
		</div>
		<!--<a href="" class="menu" title="Show page tree"></a>-->
	</div>

	<div class="controls">
		<?php /*if($pageInfos["isActive"]) { ?>
			<a href="../core/actions.php?action=togglepage&amp;pagePath=<?php echo $pageInfos["path"]; ?>" class="toggle active" title="Disable and unpublish this page"></a>
		<?php } else { ?>
			<a href="../core/actions.php?action=togglepage&amp;pagePath=<?php echo $pageInfos["path"]; ?>" class="toggle inactive" title="Enable this page"></a>
		<?php }*/ ?>
		<a href="../core/actions.php?action=publishpage&amp;pagePath=<?php echo $pageInfos["path"]; ?>" class="publish page" title="Publish this page"></a>
		<a href="../core/actions.php?action=publishall&amp;pagePath=<?php echo $pageInfos["path"]; ?>" class="publish all" title="Publish complete website"></a>
		<a href="http://www.stephanlangenegger.ch" class="yggdrasil" target="_blank"></a>
	</div>
</div></body>
</html>