
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
	<link href="css/about-stylesheet.css" rel="stylesheet" type="text/css">
	<link href="css/index-styles.css" rel="stylesheet" type="text/css">

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
			<h1><a href="index.php">INTERP</a></h1>
			<ul>
				<li>A project by <a href="http://www.jeffreythompson.org" target="_blank">Jeff Thompson</a></li>
				<li>Commissioned by <a href="http://www.turbulence.org" target="_blank">Turbulence</a></li>
			</ul>
		</header> <!-- end header -->

		<footer id="footer">	<!-- alright, semantically messy but whatever... -->

			<p><strong>INTERP</strong> is a series of digital sculptures generated by blending 100 unrelated photographs, placing them into simulated three-dimensional space, and importing them into <a href="http://en.wikipedia.org/wiki/Photogrammetry" target="_blank">photogrammetry</a> software, tricking it into thinking the photographs are of a single object.</p>

			<p>Every photograph in my library (approximately 12,000 images when the project began in 2012) were used as the input data set. In much of my work, I am interested in <a href="http://piim.newschool.edu/journal/issues/2012/02/index.php" target="_blank">"useless" and culturally-derived data sets</a>, so rather than use an arbitrary archive of photographs (a Google image search for a particular term, for example), it seemed more natural to use a finite set that I had generated.</p>

			<h2>HOW THE MODELS WERE MADE</h2>
			<p>In order to merge the photographs into 3D models using <a href="http://www.agisoft.ru/products/photoscan" target="_blank">PhotoScan</a>, the <a href="http://en.wikipedia.org/wiki/Photogrammetry" target="_blank">photogrammetry</a> software used (similar to the popular <a href="http://www.123dapp.com/catch" target="_blank">123D Catch</a> but offline and more flexible), the images had to be processed so the software would be tricked into thinking they were of the same object. After several tests, it appears that groups of 100 images that are faded into each other work the best.</p>

			<img src="images/photo-in-room.png" style="width: 100%;" alt="A photograph, placed in a false 3D room." />
			<p><em>A photograph, placed in a false 3D room.</em></p>

			<p>This process was automated using series of small programs written in <a href="http://www.processing.org" target="_blank">Processing</a> (see the <a href="https://github.com/jeffThompson/Interp" target="_blank">project GitHub page</a> for full source code). The images were interpolated one into another and saved into separate folders. Because of the way <a href="http://en.wikipedia.org/wiki/Photogrammetry" target="_blank">photogrammetry</a> works, a background is required for the software to triangulate the position of the camera. For this reason, the interpolated photographs were placed into a fake 3D room and the "room" rotated a full 360&deg; over the course of the 100 images.</p>

			<img src="images/photoscan-screenshot.png" style="width: 100%;" alt="Point cloud created by PhotoScan." />
			<p><em>Point cloud created by PhotoScan.</em></p>

			<p>The resulting images were then fed into PhotoScan for camera alignment, meshing into a 3D surface, and the creation of a texture file which overlays cut-up bits of the original photographs onto the model. The resulting 3D files were rather large (approximately 60MB each with texture files), so the models' polygon count was reduced and the texture file compressed, accomplished using a mix of <a href="http://wiki.blender.org/index.php/Doc:2.6/Manual/Extensions/Python" target="_blank">Python</a>/<a href="http://www.blender.org" target="_blank">Blender</a> automation, Photoshop batch processing, and Processing sketches. While the resulting quality is lower, the models load much faster for web viewing.</p>

			<h2>STATISTICS AND CLASSIFICATION</h2>
			<p>The resulting models include statistical data, which can also be used to sort and filter the entire set. These include the <a href="http://jeffreythompson.org/turbulence/index.php?sortby=volume">volume</a> of the model in cubic millimeters, the average <a href="http://jeffreythompson.org/turbulence/index.php?sortby=hue">hue</a> and <a href="http://jeffreythompson.org/turbulence/index.php?sortby=brightness">brightness</a> of the texture (does not include gray, blank areas), and the <a href="http://jeffreythompson.org/turbulence/index.php?sortby=year">year</a> the source photographs were taken.</p>

			<img src="images/classifications.png" style="width: 100%" alt="Model classifications: blob, satellite, box, wing, exploding." />
			<p><em>Model classifications (clockwise): blob, satellite, box, wing, exploding.</em></p>

			<p>Additionally, the resulting models displayed a surprising conformity to several basic classifications. Named for their resemblance to real-world structures, the classifications are:</p>

			<p><ul>
				<li><a href="http://jeffreythompson.org/turbulence/index.php?type=blob">blob</a>: rounded and bulbous shapes</li>
				<li><a href="http://jeffreythompson.org/turbulence/index.php?type=satellite">satellite</a>: shapes divided into multiple parts, often orbiting a main shape like a moon</li>
				<li><a href="http://jeffreythompson.org/turbulence/index.php?type=box">box</a>: rectangular shapes, usually the vestige of the fake 3D room</li>
				<li><a href="http://jeffreythompson.org/turbulence/index.php?type=wing">wing</a>: flat shapes, usually with rounded ends and rough surfaces</li>
				<li><a href="http://jeffreythompson.org/turbulence/index.php?type=exploding">exploding</a>: shapes that emanate out from a central point</li>
			</ul></p>

			<h2>SOURCE CODE, LICENSE</h2>
			<p>All <a href="https://github.com/jeffThompson/Interp">images, models, and source code</a> for this project released under a <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/"
			 target="_blank">Creative Commons, BY-NC-SA</a> license - feel free to use but <a href="mailto:mail@jeffreythompson.org">please let me know</a>.</p>

			 <h2>THANK YOU</h2>
			 <p><strong>INTERP</strong> is a 2013 commission of <a href="http://new-radio.org" target="_blank">New Radio and Performing Arts, Inc</a> for its <a href="http://www.turbulence.org" target="_blank">Turbulence.org</a> website, made possible with funding from the <a href="http://arts.gov" target="_blank">National Endowment for the Arts</a>. This project would not have been possible without their generous support.</p>

			 <p>Also thanks to the makers of <a href="http://www.fontsquirrel.com/fonts/lato" target="_blank">Lato</a>, <a href="http://fortawesome.github.io/Font-Awesome/" target="_blank">Font Awesome</a>, <a href="http://threejs.org/" target="_blank">Three.js</a>.</p>

			<p style="margin-top:60px;"><a href="http://www.jeffreythompson.org" target="_blank">www.jeffreythompson.org</a></p>

		</footer> <!-- end footer -->

	</div> <!-- end wrapper -->

</body>
</html>
