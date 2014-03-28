<?php

// Set page template
$pageSettings["template"] = "content";

// Set meta data
$pageSettings["head"]["meta"]["title"] = "Teneriffa Title";
$pageSettings["head"]["meta"]["description"] = "Teneriffa Description";
$pageSettings["head"]["meta"]["keywords"] = "Teneriffa Keywords";

?>

<y:section name="content">
	hobbyofotograf -> reisen -> teneriffa

	<y:snippet name="linklist">
		<y:link href="http://www.google.de">Link 1</y:link>
		<y:link href="http://www.yahoo.de">Link 2</y:link>
		<y:link href="http://www.20min.ch">Link 3</y:link>
	</y:snippet>

	<y:php src="php/phptest.php" />

</y:section>

<y:section name="sidebar">

	<?php for($i = 1; $i <= 3; $i++) { ?>
		<y:snippet name="teaser">
			<y:title>Teaser Titel <?php echo $i ?></y:title>
			<y:image>img</y:image>
			<y:text>Text</y:text>
		</y:snippet>
	<?php } ?>

</y:section>