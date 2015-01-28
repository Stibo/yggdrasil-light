<ul>
	<?php foreach($snippet["listitem"] as $link) { ?>
		<li>
			<a href="<?php echo $link["@href"] ?>"><?php echo $link["_content"] ?></a>
		</li>
	<?php } ?>
</ul>