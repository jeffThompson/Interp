<?php

	$v = 10.3;

	if (floor($v/10000 % 1000)) {
		$range = 10000;
	}
	else if (floor($v/1000 % 100)) {
		$range = 1000;
	}
	else if (floor($v/100 % 10)) {
		$range = 100;
	}
	else if (floor($n/10 % 1)) {
		$range = 10;
	}
	else {
		$range = 1.0;
	}

	echo '<p>input: ' . $v . '</p>';
	echo '<p>range: ' . $range . '</p>';
	echo '<p>output: ' . $v . ' &mdash; ' . ($v + $range) . '</p>';
?>