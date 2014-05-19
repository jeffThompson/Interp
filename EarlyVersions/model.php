
<!DOCTYPE html>
<html>
<head>

	<!--
		INTERP
		Jeff Thompson | 2013-14 | www.jeffreythompson.org

		INTERP is a 2013 commission of New Radio and Performing Arts, Inc, for
		its Turbulence.org website. It was made possible with funding from the
		National Endowment for the Arts.

		TO DO:
		+ how to get model volume
		+ reduce model complexity (smaller files) but preserve full-res download
			- while doing this, also resize to same dims?
		+ get actual model in
		+ missing models (14)

		+ loading animation - <i class="icon-spinner icon-spin icon-large"></i>
	-->

	<?php

		# get # of files in directory
		$files = glob('./images/*.png');
		$num_models = count($files);

		# get model name from url argument
		$model_name = '';
		if (isset($_GET["model"])) {
			$model_name = $_GET["model"];
		}
		else {
			$model_name = array_rand($files);	# if not listed, get a random one... :)
		}

		# prev/next model filename
		$prev_model_name = intval($model_name) - 1;								# previous #
		if ($prev_model_name < 0) {												# wrap if past 0
			$prev_model_name = $num_models - 1;
		}
		$prev_model_name = str_pad($prev_model_name, 3, '0', STR_PAD_LEFT);		# add leading 0s
		
		$next_model_name = intval($model_name) + 1;
		if ($next_model_name >= $num_models) {
			$next_model_name = 0;
		}
		$next_model_name = str_pad($next_model_name, 3, '0', STR_PAD_LEFT);

		# get filesize in proper format
		# via: http://stackoverflow.com/a/5501447/1167783
		$file_size = filesize('images/' . $model_name . '.png');
		if ($file_size >= 1048576) $file_size = number_format($file_size / 1048576, 2) . '&nbsp;' . 'MB';
		else if ($file_size >= 1024) $file_size = number_format($file_size / 1024, 2) . '&nbsp;' . 'kb';
		else $file_size = $file_size . '&nbsp;' . 'bytes';

		# get stats
		$stats = array(
			'brightness' => 255.0,
			'hue' => 140.3,
			'volume' => 1572.9,
			'type' => 'b-s-w',
			'date' => 2003
		);
		$use_hash = false;
	?>

	<title>INTERP #<?php echo $model_name; ?></title>

	<meta charset="UTF-8">
	<link href="stylesheet.css" rel="stylesheet" type="text/css">
	<link href="model-styles.css" rel="stylesheet" type="text/css">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

	<style>
		/*html {
			<?php echo 'background: url(images/' . $model_name . '.png) no-repeat center center fixed;' ?>
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}*/
	</style>
</head>

<body>

	<div id="wrapper">
		<section id="modelDetail">

			<!-- LINK TO INDEX -->
			<p>
				<?php echo '<a href="model.php?model=' . $prev_model_name . '" class="navArrow">&larr;</a>&nbsp;&nbsp;&nbsp;' ?>
				<a href="index.php" id="navLink">INTERP</a>
				<?php echo '&nbsp;&nbsp;&nbsp;<a href="model.php?model=' . $next_model_name . '" class="navArrow">&rarr;</a>' ?>
			</p>

			<!-- MODEL -->
			<?php echo '<img src="images/' . $model_name . '.png" />' . PHP_EOL; ?>

			<!-- STATS, formatted as a list -->
			<div id="stats">
				<ul>
					<?php 
						echo '<li><span id="modelName">' . $model_name . '</span></li>';	# model name (large, in bold)
						echo PHP_EOL . '					';								# format nice :)
						
						foreach ($stats as $key => $value) {								# load stats for model
							echo '<li>';
							
							# display key (w custom class for styling as needed)
							echo '<span class="key">' . ucfirst($key) . ':</span>';

							# numerical stats (floor rounds down - index page will get range of 0 to +1)
							if ($key == 'brightness' || $key == 'hue' || $key == 'volume') {
								echo '<a href="index.php?' . $key . '=' . floor($value) . '">' . $value . '</a>';
							}

							# type as list of types
							else if ($key == 'type') {
								$types = explode('-', $value);		# split into list of types
								$num_types = count($types);			# how many? used for commas
								
								for ($i=0; $i<$num_types; $i++) {
									$type = $types[$i];
									switch ($type) {
										case 'w': $type = 'wing'; break;
										case 'x': $type = 'exploding'; break;
										case 'o': $type = 'box'; break;
										case 'b': $type = 'blob'; break;
										case 's': $type = 'satellite'; break;
									}
									echo '<a href="index.php?type=' . $type . '">' . $type . '</a>';

									if ($i < $num_types - 1) {
										echo ',&nbsp;';
									}
								}
							}

							# year
							else if ($key == 'date') {
								echo '<a href="index.php?date=' . $value . '">' . $value . '</a>';
							}

							echo '</li>';									# end list item
							echo PHP_EOL . '					';			# format nice :)
						}
						
						# download link
						echo '<li id="downloadLink"><a href="./models/' . $model_name . '.obj" title="Download ' . $model_name . '.obj..."><span id="download"><i class="fa fa-download"></i>&nbsp;&nbsp;(' . $file_size . ')</span></a></li>' . PHP_EOL;
					?>
				</ul>
			</div> <!-- end stats -->

		</section> <!-- end models -->
	</div> <!-- end wrapper -->

</body>
</html>
