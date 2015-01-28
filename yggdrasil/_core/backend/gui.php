<?php include YGGDRASIL_BACKEND_CORE_DIR . "backend" . DS . "css.php"; ?>

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
			<a href="_core/actions.php?action=togglepage&amp;pagePath=<?php echo $pageInfos["path"]; ?>" class="toggle active" title="Disable and unpublish this page"></a>
		<?php } else { ?>
			<a href="_core/actions.php?action=togglepage&amp;pagePath=<?php echo $pageInfos["path"]; ?>" class="toggle inactive" title="Enable this page"></a>
		<?php }*/ ?>
		<a href="_core/actions.php?action=publishpage&amp;pagePath=<?php echo $pageInfos["path"]; ?>" class="publish page" title="Publish this page"></a>
		<a href="_core/actions.php?action=publishall&amp;pagePath=<?php echo $pageInfos["path"]; ?>" class="publish all" title="Publish complete website"></a>
		<a href="http://www.stephanlangenegger.ch" class="yggdrasil" target="_blank"></a>
	</div>
</div>