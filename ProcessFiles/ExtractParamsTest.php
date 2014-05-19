
<?php

	# extract params test

	$tags = array( 'brightness', 'hue', 'volume', 'type', 'year' );
	
	echo '<ul>';
	foreach ($tags as $tag) {
	
		# if tag is set in URL, bold
		if (array_key_exists($tag, $_GET)) {
			$v = floor($_GET[$tag]);
			echo '<li><strong>X ' . $tag . ': ' . $v . '-' . ($v+1) . '</strong></li>';
		}

		# if sorting params set, bold
		else if (isset($_GET['sortby']) && $_GET['sortby'] == $tag) {
			echo '<li><strong>X ' . $tag . '</strong></li>';
		}

		# otherwise, list without formatting
		else {
			echo '<li>' . $tag . '</li>';
		}
	}
	echo '</ul>';

?>