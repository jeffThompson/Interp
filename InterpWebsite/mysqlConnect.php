<?php
	
	// connect to the database
	// this info is safe, since anyone running this script won't see anything!
	DEFINE ('DB_USER', 'dbo524321369');
	DEFINE ('DB_PASSWORD', 'dru?Et7j');
	DEFINE ('DB_HOST', 'db524321369.db.1and1.com');
	DEFINE ('DB_NAME', 'db524321369');
	$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR DIE ('ERROR: could not connect to the database!  Sorry...\n' . mysqli_connection_error() );
	
	// set encoding
	mysqli_set_charset($dbc, 'utf8');
?>