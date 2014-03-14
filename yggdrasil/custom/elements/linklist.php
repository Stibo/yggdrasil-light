<ul>
	<?php foreach($contentElement->link as $link) { ?>
		<li>
			<a href="<?php echo $link["href"] ?>"><?php echo $link ?></a>
		</li>
	<?php } ?>
</ul>