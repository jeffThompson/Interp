<?php

	/*
		INTERP
		Jeff Thompson | 2013-14 | www.jeffreythompson.org

		INTERP is a 2013 commission of New Radio and Performing Arts, Inc, for
		its Turbulence.org website. It was made possible with funding from the
		National Endowment for the Arts.
		
	*/


	# creates a zip archive of model and related files for downloading
	# mostly via: http://stackoverflow.com/a/20216192
	if (isset($_GET['model'])) {

		# list of files to add to zip
		$model_name = $_GET['model'];
		$files = array('models/' . $model_name . '.obj', 'models/' . $model_name . '.mtl', 'models/' . $model_name . '.jpg');
		
		# create temp file and zip archive
		$tmp = tempnam('.', '');
		$zip = new ZipArchive();
		$zip -> open($tmp, ZipArchive::CREATE);

		foreach ($files as $file) {
			$f = file_get_contents($file);					# prep file to add
			$zip -> addFromString(basename($file), $f);		# add to zip
		}
		$zip -> close();
		
		# send to browser for download
		header('Content-disposition: attachment; filename=' . $model_name . '.zip');
	    header('Content-type: application/zip');
	    readfile($tmp);

	    # when done, delete temporary file and redirect back to model page
	    unlink($tmp);
    	header('Refresh: 0; url=model.php?model=' . $model_name);
	}

	# if no model name is set, send back to index
	else {
    	header('Refresh: 0; url=index.php');	
	}

	# all done, add redirect notice as a safeguard...
	echo '<html><head></head><body><h1>Downloading model...</h1><p>If your browser doesn\'t automatically redirect, <a href="index.php">click here</a>.</p></body></html>';
?>
