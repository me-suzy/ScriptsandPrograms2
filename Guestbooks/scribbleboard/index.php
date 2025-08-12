<?php
	require_once('init.php');
	// Generate a random string to make browsers request a fresh copy of the image
	// every time the site is loaded. This works better than cache headers which
	// can easily get ignored.
	$random = md5(time());
	require_once('template/indexpage.htm');
?>
