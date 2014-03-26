<?php

// Set page template
$this->settings["template"] = "home";

// Set meta data
$this->settings["head"]["meta"]["title"] = "Homepage Title";
$this->settings["head"]["meta"]["description"] = "Homepage Description";
$this->settings["head"]["meta"]["keywords"] = "Homepage Keywords";

?>

<y:section name="content">

	<y:snippet name="linklist">
		<y:link href="http://www.google.de">Link 1</y:link>
		<y:link href="http://www.yahoo.de">Link 2</y:link>
		<y:link href="http://www.20min.ch">Link 3</y:link>
	</y:snippet>

</y:section>

<y:section name="sidebar">

	<?php for($i = 1; $i <= 5; $i++) { ?>
		<y:snippet name="teaser">
			<y:title>Teaser Titel <?php echo $i ?></y:title>
			<y:image>img</y:image>
			<y:text>Text</y:text>
		</y:snippet>
	<?php } ?>

</y:section>