<!DOCTYPE html>
<html lang="en">
	<head>
	
		<!-- 
			PHOTOGRAPHS > 123D CATCH > 3D MODEL
			Jeff Thompson | 2013 | www.jeffreythompson.org
			
			Created with generous funding from Turbulence.org
			
			TO DO:
			+ fixed-width for rotate link (so "download" doesn't move back and forth)?
			+ arrows also reset the timer for fadeout
			
		-->
	
		<title>3d Test - Load STL, Rotate, Interaction</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

		<!-- import Source Sans Pro + Font Awesome glyphs -->
		<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300italic,300,400italic,600,600italic,700,700italic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
		
		<!-- load all files in the 'models' folder into an array, store as JSON -->
		<?php
			$files = array();
			foreach (glob("models/*.stl") as $filename) {

				# get filesize in proper format
				# via: http://stackoverflow.com/a/5501447/1167783
				$size = filesize($filename);
				if ($size >= 1048576) $size = number_format($size / 1048576, 2) . ' MB';
				else if ($size >= 1024) $size = number_format($size / 1024, 2) . ' kb';
				else $size = $size . ' bytes';
				
				# add to array
				$files[] = array("filename" => basename($filename), "filesize" => $size);
			}
			
			# encode to JSON for our Javascript to read later
			$files = json_encode($files);
		?>
		<script>
			// load models		
			// via: http://www.dyn-web.com/tutorials/php-js/json.php
			var modelFilenames = new Array();											// empty array for filenames
			var filesJSON = JSON.parse('<?php echo $files ?>');		// get from JSON, store as dict
			for (i in filesJSON) {																// iterate listings, store in array
				modelFilenames.push(filesJSON[i].filename);					// append to list of files
			}
		</script>
		
		<script>			
			// model setup variables (here for convenience)
			var scale = 0.5;						// amt to scale model (1.0 = normal size, 0.5 = 50%)
			var rotSpeed = 0.003;					// speed model auto-rotates
			var objectColor = 0xcc9900;
			var objectShadowColor = 0x000000;
			var objectReflectionColor = 0x444444;
			var backgroundColor = 0x222222;
			var statistics = false;					// show FPS
			var rotateModel = true;					// rotate model automatically? toggle with link
			var fadeInTime = 500;					// time to fade back in (ms)
			var fadeOutTime = 1000;					// ditto fade out										
			var fadeTimer = 5000;					// how long to wait until fading out (ms)
			var rotateOnNewModel = false;			// restart auto-rotate when a new model is loaded?

			// index to access filenames from array
			// if a permalink, the filename will be specified after a #
			var whichModel = 0;
			var modelFilenameArgument = window.location.hash.substring(1);		// http://stackoverflow.com/a/10076097/1167783
			for (var i=0; i<modelFilenames.length; i++) {
				if (modelFilenames[i] === modelFilenameArgument) {
					whichModel = i;
					break;
				}
			}
		</script>
		
		<!-- load three.js and jquery -->
		<script src="js/three.min.js"></script>
		<script src="js/STLLoader.js"></script>
		<script src="js/TrackballControls.js"></script>
		<script src="js/Detector.js"></script>
		<script src="js/stats.min.js"></script>
		<script src="js/jquery-1.10.2.min.js"></script>
		
		<!-- hide info when not active -->
		<script>
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
					$('#info').fadeIn(fadeInTime);
					$('#arrow-left').fadeIn(fadeInTime);
					$('#arrow-right').fadeIn(fadeInTime);
					$('html').css({
							cursor: ''
					});
				} 
				else {
					fadeInBuffer = false;
				}
				
				timer = setTimeout(function() {
					$('#info').fadeOut(fadeOutTime);
					$('#arrow-left').fadeOut(fadeOutTime);
					$('#arrow-right').fadeOut(fadeOutTime);
					$('html').css({
						cursor: 'none'
					});
					fadeInBuffer = true
				}, fadeTimer)
			});			
		});
		
		// arrow keys to advance
		// cross-browser solution via: http://stackoverflow.com/a/6011119
		$(document).keydown(function(e) {
			switch(e.which) {
				case 37:									// L
					prevModel();
					break;
				case 39:									// R
					nextModel();
					break;
				case 38: break;						// skip U/D because they do weird things in FF
				case 40: break;
				case 32: 
					toggleModelRotation();	// spacebar pauses and resumes rotation
					break;
				default: return;					// skip all other keys
			}
			e.preventDefault();
		});
		
		// set overflow to hidden; prevents some weird scrolling stuff
		// also: for some reason, needs to be here and not up in the top...
		$('body,html').css('overflow','hidden');
			
		</script>	
	</head>
	
	<body>
	
		<!-- display info/instructions -->
		<div id="info">
			<p id="modelFilename">TEST.STL</p>
			
			<p id="commands"><a id="rotate" href="javascript:void(0)" onclick="toggleModelRotation()">pause</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a id="download" href="#" target="_blank">download</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a id="permalink" href="index.php">permalink</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="index.php">more info</a></p>
			<!--
			<p id="commands"><a id="rotate" href="javascript:void(0)" onclick="toggleModelRotation()">pause</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a id="download" href="#" target="_blank">download</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a id="permalink" href="index.php">permalink</a></p>
			-->
			
			<p id="instructions">click-and-drag / scrollwheel to interact</p>

			<!-- info button back to index -->
			<!-- <p id="infoButton"><a href="index.php">i</a></p> -->	
		</div>  <!-- end info -->
		
		<!-- arrows -->
		<!-- <img id="arrow-left" src="images/slideshowArrow-left.png" onclick="prevModel()">
		<img id="arrow-right" src="images/slideshowArrow-right.png" onclick="nextModel()"> -->
		<!-- use: icon-caret-right, icon-angle-right -->
		<p id="arrow-left" onclick="prevModel()"><i class="icon-angle-left"></i></p>
		<p id="arrow-right" onclick="nextModel()"><i class="icon-angle-right"></i></p>
				
		<!-- display 3d model -->
		<script src="js/LoadAndDisplaySTLFile.js"></script>
		
	</body>
</html>
