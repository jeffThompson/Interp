
<html> 
<head> 

	<!--
		INTERP
		Jeff Thompson | 2013-14 | www.jeffreythompson.org

		INTERP is a 2013 commission of New Radio and Performing Arts, Inc, for
		its Turbulence.org website. It was made possible with funding from the
		National Endowment for the Arts.
		
	-->
	
	<?php
		# get # of files in directory
		$files = glob('./models/*.obj');
		$num_models = count($files);

		# get model name from url argument
		$model_name = '';
		if (isset($_GET['model'])) {
			$model_name = $_GET['model'];
		}
		else {
			$model_name = array_rand($files);		# if not listed, get a random one... :)
		}

		# load stats from database
		require('mysqlConnect.php');
		$q = "SELECT * FROM `models` WHERE `index`='" . $model_name . "'";
		$r = @mysqli_query($dbc, $q);
		if ($r) {
			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			$stats = array(
				'brightness' => $row['brightness'],
				'hue' => $row['hue'],
				'volume' => $row['volume'],
				'type' => $row['type'],
				'year' => $row['year']
			);
			mysqli_free_result($r);
		}
		$use_hash = false;
		mysqli_close($dbc);

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
		$file_size = filesize('models/' . $model_name . '.obj');
		if ($file_size >= 1048576) $file_size = number_format($file_size / 1048576, 2) . '&nbsp;' . 'MB';
		else if ($file_size >= 1024) $file_size = number_format($file_size / 1024, 2) . '&nbsp;' . 'kb';
		else $file_size = $file_size . '&nbsp;' . 'bytes';
	?>

	<title>INTERP #<?php echo $model_name; ?></title>

	<meta charset="UTF-8">
	<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
	<link href="css/model-styles.css" rel="stylesheet" type="text/css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

	<script src="js/three.js"></script>
	<script src="js/TrackballControls.js"></script>
	<script src="js/OBJMTLLoader.js"></script>
	<script src="js/MTLLoader.js"></script>
	<script src="js/LoadAndDisplay3dModel.js"></script>
	<script src="js/jquery-1.11.1.min.js"></script>

	<!-- variables (pass from PHP - json_encode() assures valid JS) -->
	<script>
		var modelName = <?php echo json_encode($model_name); ?>;
		var nextModelName = <?php echo json_encode($next_model_name); ?>;
		var prevModelName = <?php echo json_encode($prev_model_name); ?>;
		var rotSpeed = 0.005;
		var fadeInTime = 500;					// time to fade back in (ms)
		var fadeOutTime = 1000;					// ditto fade out										
		var fadeTimer = 5000;					// how long to wait until fading out (ms)
		
		// fade out nav and stats when inactive
		// via: http://stackoverflow.com/a/15532514/1167783
		$(function() {
			var timer;
			var fadeInBuffer = false;
			$(document).mousemove(function() {
				if (!fadeInBuffer) {
					if (timer) {
						clearTimeout(timer);
						timer = 0;
					}
					$('#nav').fadeIn(fadeInTime);
					$('#stats').fadeIn(fadeInTime);
					$('html').css({
							cursor: ''
					});
				} 
				else {
					fadeInBuffer = false;
				}
				
				timer = setTimeout(function() {
					$('#nav').fadeOut(fadeOutTime);
					$('#stats').fadeOut(fadeOutTime);
					$('html').css({
						cursor: 'none'
					});
					fadeInBuffer = true
				}, fadeTimer)
			});			
		});

		$(document).keydown(function(e) {
		    if (e.keyCode == 39) {
		        window.location = 'model.php?model=' + nextModelName;
		    }
		    else if (e.keyCode == 37) {
		        window.location = 'model.php?model=' + prevModelName;
		    }

		});
	</script>
</head>

<body>
	<div id="wrapper">
		
		<section id="modelDetail">

			<!-- LINK TO INDEX -->
			<p id="nav">
				<?php echo '<a href="model.php?model=' . $prev_model_name . '" class="navArrow">&larr;</a>&nbsp;&nbsp;&nbsp;' . PHP_EOL ?>
				<a href="index.php" id="navLink">INTERP</a>
				<?php echo '&nbsp;&nbsp;&nbsp;<a href="model.php?model=' . $next_model_name . '" class="navArrow">&rarr;</a>' . PHP_EOL ?>
			</p>

			<!-- MODEL! -->
			<div id="model">
				<section id="loadingAnimation">
					<i class="fa fa-spinner fa-spin fa-3x"></i>
					<p>Loading model...</p>
				</section>
			</div> <!-- end model -->
		
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

							# numerical stats
							if ($key == 'brightness' || $key == 'hue' || $key == 'volume') {
								# -0 forces float (gets rid of sci notation)
								echo '<a href="index.php?' . $key . '=' . $value . '">' . (float)$value . '</a>';
							}

							# type as list of types
							else if ($key == 'type') {
								$types = explode('-', $value);		# split into list of types
								$num_types = count($types);			# how many? used for commas
								
								for ($i=0; $i<$num_types; $i++) {
									echo '<a href="index.php?type=' . $types[$i] . '">' . $types[$i] . '</a>';

									if ($i < $num_types - 1) {
										echo ',&nbsp;';
									}
								}
							}

							# year
							else if ($key == 'year') {
								echo '<a href="index.php?year=' . $value . '">' . $value . '</a>';
							}

							echo '</li>';								# end list item
							echo PHP_EOL . '					';		# format nice :)
						}
						
						# download link (sends to download.php for zipping)
						echo '<li id="downloadLink"><a href="download.php?model=' . $model_name . '" title="Download ' . $model_name . '.obj..."><span id="download"><i class="fa fa-download"></i>&nbsp;&nbsp;(' . $file_size . ')</span></a></li>' . PHP_EOL;
					?>
				</ul>
			</div> <!-- end stats -->
		</section> <!-- end models -->
	</div> <!-- end wrapper -->

	<!-- LOAD MODEL, INTERACT -->
	<script>
		init();
		animate();
	</script> 
 
 </body> 
 </html>
