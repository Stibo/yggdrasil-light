<?php

// Set page template
$this->settings["template"] = "content";

// Set meta data
$this->settings["head"]["meta"]["title"] = "Teneriffa Title";
$this->settings["head"]["meta"]["description"] = "Teneriffa Description";
$this->settings["head"]["meta"]["keywords"] = "Teneriffa Keywords";

?>

<y:section name="content">
hobbyofotograf -> reisen -> teneriffa
	<y:element name="linklist">
		<y:link href="http://www.google.de">Link 1</y:link>
		<y:link href="http://www.yahoo.de">Link 2</y:link>
		<y:link href="http://www.20min.ch">Link 3</y:link>
	</y:element>

</y:section>

<y:section name="sidebar">

	<?php for($i = 1; $i <= 5; $i++) { ?>
		<y:element name="teaser">
			<y:title>Teaser Titel <?php echo $i ?></y:title>
			<y:image>img</y:image>
			<y:text>Text</y:text>
		</y:element>
	<?php } ?>

</y:section>