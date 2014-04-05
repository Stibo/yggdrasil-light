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

				$publishDate = $this->page->getPublishDate(true);

				echo ($publishDate != -1) ? $publishDate : "Not yet published";

			?>
		</div>
		<!--<a href="" class="menu" title="Show page tree"></a>-->
	</div>

	<div class="controls">
		<?php /*if($this->page->isActive()) { ?>
			<a href="../core/actions.php?action=togglepage&amp;pagePath=<?php echo $this->page->pageInfos["path"]; ?>" class="toggle active" title="Disable and unpublish this page"></a>
		<?php } else { ?>
			<a href="../core/actions.php?action=togglepage&amp;pagePath=<?php echo $this->page->pageInfos["path"]; ?>" class="toggle inactive" title="Enable this page"></a>
		<?php }*/ ?>
		<a href="../core/actions.php?action=publishpage&amp;pagePath=<?php echo $this->page->pageInfos["path"]; ?>" class="publish page" title="Publish this page"></a>
		<a href="../core/actions.php?action=publishall&amp;pagePath=<?php echo $this->page->pageInfos["path"]; ?>" class="publish all" title="Publish complete website"></a>
		<a href="http://www.stephanlangenegger.ch" class="yggdrasil" target="_blank"></a>
	</div>
</div>