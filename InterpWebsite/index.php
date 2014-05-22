
<!DOCTYPE html>
<html lang="en">
<head>

	<!--
		INTERP
		Jeff Thompson | 2013-14 | www.jeffreythompson.org

		INTERP is a 2013 commission of New Radio and Performing Arts, Inc, for
		its Turbulence.org website. It was made possible with funding from the
		National Endowment for the Arts.
		
	-->

	<title>INTERP&nbsp;&nbsp;++&nbsp;&nbsp;Jeff Thompson</title>
	
	<meta charset="UTF-8">
	<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
	<link href="css/index-styles.css" rel="stylesheet" type="text/css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

	<script>
		function resizeFont(fontTag) {
			var newFontSize = window.innerWidth / 6;		// resize dynamically
			if (newFontSize < 72) newFontSize = 72;			// if too small, set to 72px
			document.getElementsByTagName('h1')[0].style.fontSize = newFontSize + 'px';
		}

		function map(value, low1, high1, low2, high2) {
    		return low2 + (high2 - low2) * (value - low1) / (high1 - low1);
		}

		// also do this on window resize...
		window.onresize = function(event) {
			resizeFont('h1');
		}
	</script>

	<!-- analytics -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-51138924-1', 'turbulence.org');
		ga('send', 'pageview');
	</script>
</head>

<body onload="resizeFont('h1')">
	<div id="wrapper">

		<!-- HEADER -->
		<header id="header">
			<h1>INTERP</h1>
			<ul>
				<li>A project by <a href="http://www.jeffreythompson.org" target="_blank">Jeff Thompson</a></li>
				<li>Commissioned by <a href="http://www.turbulence.org" target="_blank">Turbulence</a></li>
			</ul>
		</header> <!-- end header -->


		<!-- SORTING PARAMETERS -->
		<section id="sortParams">
			<ul>
				<li>SORT BY</li>

				<?php

					# connect to database...
					require('mysqlConnect.php');
					$tags = array( 'brightness', 'hue', 'volume', 'year' );

					# if sorting params set
					if (isset($_GET['sortby'])) {
						$sort_by = $_GET['sortby'];
						$q = "SELECT * FROM `models` ORDER BY `" . $sort_by . "` ASC";
					}
					
					# if keywords set, return only within certain ranges
					else if (isset($_GET['brightness'])) {
						$v = floor($_GET['brightness']);
						$q = "SELECT * FROM `models` WHERE `brightness` BETWEEN " . $v . " AND " . ($v+1) . " ORDER BY `brightness` ASC";
					}
					else if (isset($_GET['hue'])) {
						$v = floor($_GET['hue']);
						$q = "SELECT * FROM `models` WHERE `hue` BETWEEN " . $v . " AND " . ($v+1) . " ORDER BY `hue` ASC";
					}
					else if (isset($_GET['volume'])) {
						$v = floor($_GET['volume']);
						
						# responsive range for volume
						# 1, 10, 100, 1k, 10k
						if ($v < 1) {
							$range = 1.0; 
						}
						else if ($v >= 1 && $v < 10) {
							$range = 9.0; 
						}
						else if ($v >= 10 && $v < 100) {
							$range = 90.0; 
						}
						else if ($v >= 100 && $v < 1000) {
							$range = 100.0; 
						}
						else if ($v >= 1000 && $v < 10000) {
							$range = 1000.0;
						}
						else {
							$range = 10000.0;
						}

						$q = "SELECT * FROM `models` WHERE `volume` BETWEEN " . $v . " AND " . ($v + $range) . " ORDER BY `volume` ASC";
					}
					else if (isset($_GET['date'])) {
						$q = "SELECT * FROM `models` WHERE `date`=" . $_GET['date'] . " ORDER BY `index` ASC";
					}

					# if type is set (like a tag, we have to extract from comma-separated list)
					else if (isset($_GET['type'])) {
						$q = "SELECT * FROM `models` WHERE INSTR(`type`, '" . $_GET['type'] . "') > 0";
					}

					# no sorting or keywords, return ALL models...
					else {
						$q = "SELECT * FROM `models` ORDER BY `index` ASC";
					}

					# write sorting tags to HTML
					foreach ($tags as $tag) {
					
						# if tag is set in URL, bold and set unlink
						if (array_key_exists($tag, $_GET)) {
							$v = floor($_GET[$tag]);
							echo '<li><a href="index.php" class="sortSelected">';
							echo '<i class="fa fa-times"></i>&nbsp;';
							echo ucfirst($tag) . ': ' . $v . '-' . ($v+1) . '</a></li>';
							echo PHP_EOL . '				';
						}

						# if sorting params set, bold and set unlink
						else if (isset($_GET['sortby']) && $_GET['sortby'] == $tag) {
							echo '<li><a href="index.php" class="sortSelected">';
							echo '<i class="fa fa-times"></i>&nbsp;';
							echo ucfirst($tag) . '</a></li>';
							echo PHP_EOL . '				';
						}

						# otherwise, list without formatting
						else {
							echo '<li><a href="index.php?sortby=' . $tag . '">';
							echo ucfirst($tag) . '</a></li>';
							echo PHP_EOL . '				';
						}
					}

					# if ype is set (not in tag list), bold and set unlink
					if (isset($_GET['type'])) {
						echo '<li><a href="index.php" class="sortSelected">';
						echo '<i class="fa fa-times"></i>&nbsp;';
						echo 'Type: ' . ucfirst($_GET['type']) . '</a></li>';
						echo PHP_EOL . '				';
					}

					echo PHP_EOL;
				?>
			</ul>
		</section>


		<!-- MODEL THUMBNAILS -->
		<section id="models">
			<ul class="thumbnails">
				<?php

					# version for DBO connection (on Turbulence server)
					foreach($dbc -> query($q) as $row) {
						$model_number = $row['index'];
						echo '<li>';
						echo '<a href="model.php?model=' . $model_number . '">';											# for link to new page
						echo '<img src="thumbnails/' . $model_number . '.png" alt="3D model #' . $model_number . '" />';	# thumbnail image
						# echo '<p>' . $model_number . '</p>';																# model # as link
						echo '</a>';
						echo '</li>' . PHP_EOL . '				';															# format nice :)
					}
					echo PHP_EOL;
					$dbc = null;

					# version for modern server configuration using mysqli_query()
					// $r = @mysqli_query ($dbc, $q);
					// if ($r) {
					// while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					// 	$model_number = $row['index'];
					// 	echo '<li>';
					// 	echo '<a href="model.php?model=' . $model_number . '">';											# for link to new page
					// 	echo '<img src="thumbnails/' . $model_number . '.png" alt="3D model #' . $model_number . '" />';	# thumbnail image
					// 	# echo '<p>' . $model_number . '</p>';																# model # as link
					// 	echo '</a>';
					// 	echo '</li>' . PHP_EOL . '				';															# format nice :)
				 	// }			
					// 	mysqli_free_result($r);
				 	// }
					// echo PHP_EOL;
					// mysqli_close($dbc);
				?>
			</ul>
		</section> <!-- end models -->


		<!-- FOOTER -->
		<div class="clear"></div>
		<footer id="footer">
			<p><strong>INTERP</strong> is a 2013 commission of <a href="http://new-radio.org" target="_blank">New Radio and Performing Arts, Inc</a> for its <a href="http://www.turbulence.org" target="_blank">Turbulence.org</a> website, made possible with funding from the National Endowment for the Arts. <a href="about.php">More about the project and how it was made...</a></p>

			<p>All <a href="https://github.com/jeffThompson/Interp">images, models, and code</a> released under a <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/"
			 target="_blank">Creative Commons, BY-NC-SA</a> license.</p>

			<ul style="margin-top:60px;">
				<li><a href="http://www.turbulence.org" target="_blank">&larr; <span style="font-weight:400">back to</span> Turbulence</a></li>
				<li><a href="http://www.jeffreythompson.org" target="_blank">www.jeffreythompson.org</a></li>
			</ul>
		</footer> <!-- end footer -->


	</div> <!-- end wrapper -->

</body>
</html>