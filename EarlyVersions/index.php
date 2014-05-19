<!DOCTYPE html>
<html lang="en">
	<head>
	
		<!-- 
			PHOTOGRAPHS > 123D CATCH > 3D MODEL
			Jeff Thompson | 2013 | www.jeffreythompson.org
			
			Created with generous funding from Turbulence.org
			
			TO DO:
			
		-->
	
		<title>Photographs >> 3d Models | Jeff Thompson</title>
		<meta charset="utf-8">
		<!-- <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"> -->
		
		<!-- load fonts -->
		<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300italic,300,400italic,600,600italic,700,700italic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css">	
		<link rel="stylesheet" type="text/css" href="css/font-awesome.css">

		<!-- import jquery -->
		<script src="js/jquery-1.10.2.min.js"></script>
	</head>
	
	<body>
	
		<div id="intro">
		
			<!-- <img id="modelScreenshot" src="images/model-screenshot.png" /> -->
			<h1>EVERY PHOTOGRAPH I EVER TOOK CONVERTED TO 3D MODELS</h1>
			
			<p>My <a href="javascript:void(0)" title="Well, not my entire library: the images used are every DIGITAL photograph I have ever taken, but does not include film photographs made before 2004. But really, we're talking like 200-300 photographs compared to 12,000+ images.">entire photo library</a> (approximately 12,300 images from 2002-2013) is interpolated, placed in a virtual 3d space, and imported to <a href="http://www.agisoft.ru/products/photoscan">Agisoft's Photoscan</a> (a more hackable version of AutoDesk's free <a href="http://www.123dapp.com/catch">123D Catch</a> software). The software attempts to create a 3d model from what it supposes are photographs of the same object.</p>

			<p>This site requires JavaScript and runs best in Chrome or Firefox. <a id="toggleDiv" href="javascript:void(0)">Safari users will need to follow these instructions</a> to enable WebGL.</p>
			
		<!-- dropdown instructions Safari users -->
		<div id="safariInstructions">
			<a href="javascript:void(0)" id='closeDiv'><i class='icon-remove'></i></a>
			<h2><em>INSTRUCTIONS FOR SAFARI USERS</em></h2>
			<p>By default, Safari disables WebGL, the 3d engine used in this project. To enable it, Mac users should do the following:</p>
			<ol>
				<li>In the "Safari" menu, select "Preferences"...</li>
				<li>Click the "Advanced" tab</li>
				<li>At the bottom of the window, check the "Show Develop menu in menu bar" checkbox</li>
				<li>Close the preferences</li>
				<li>In the "Develop" menu select "Enable WebGL"</li>
			</ol>
			<p><em>PC users: sadly, WebGL is not supported in Safari for Windows; please try Chrome or Firefox</em></p>
		</div> <!-- end safariInstructions -->

			<p style="margin-top:40px"><em>Created with generous funding from <a href="http://www.turbulence.org">Turbulence.org</a></em></p>

			<!-- view project -->
			<h2 id="viewProject"><a href="gallery.php">VIEW PROJECT</a></h2>
		
			<div id="byline">
				<p><a href="http://www.jeffreythompson.org">Jeff Thompson</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;2013&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://creativecommons.org/licenses/by-nc-sa/2.0">Creative Commons NC-A-SA</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://www.github.com/jeffthompson/EveryPhotographIEverTookConvertedTo3dModels">Project Code Repository</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://www.jeffreythompson.org/downloads/EveryPhotographs3dModels.zip">Download All Models</a></p>
			</div>	
		
		</div> <!-- end intro -->
	
		
		<script>
			$('#toggleDiv, #closeDiv').click(function() {
				$('#safariInstructions').slideToggle('slow');
			});
		</script>

	</body>
</html>
