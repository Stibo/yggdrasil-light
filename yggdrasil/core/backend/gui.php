<style type="text/css">
	#yggdrasil-admin {
		position: fixed;
		bottom: 0;
		right: 0;
		font-size: 0;
		line-height: 0;
	}

	#yggdrasil-admin a {
		display: inline-block;
		vertical-align: top;
		height: 50px;
		width: 50px;
		margin-right: 2px;
		background-color: black;
		font-size: 12px;
		line-height: 50px;
		color: white;
		text-align: center;
	}
</style>

<div id="yggdrasil-admin">
	<a href="">W/E</a>
	<a href="actions.php?action=togglepage&amp;pagePath=<?php echo $this->path; ?>">
		<?php if($this->isInactive) { ?>
			TS
		<?php } else { ?>
			TH
		<?php }?>
	</a>
	<a href="actions.php?action=publishpage&amp;pagePath=<?php echo $this->path; ?>">PP</a>
	<a href="actions.php?action=publishsubpages&amp;pagePath=<?php echo $this->path; ?>">PSP</a>
	<a href="actions.php?action=publishall">PA</a>
	<!--<a href="">T</a>-->
</div>