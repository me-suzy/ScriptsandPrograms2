<?php
	require_once('init.php');
	// Was the last post made before the timeout specified?
	if (!empty($_SESSION['lastpost']) && ((time() - $_SESSION['lastpost']) < NEXT_POST_TIMEOUT)) {
		// Yes, so we show an error. Notice that we divide the number of
		// seconds by 60 to get the number of minutes. The number is also
		// ceil'd (rounded upwards regardless of the decimal value) so it
		// doesn't look weird.
		Error('You need to wait at least '. ceil((NEXT_POST_TIMEOUT / 60)) .' minutes before making another post.');
	}
	// If there are no coordinates passed over, we're going to have to Error out.
    if ($_SERVER['QUERY_STRING'] == '') {
        Error('The position coordinates are missing.');
    } else {
    	// Make an array of the coordinates.
        $tmp = explode(',',$_SERVER['QUERY_STRING']);
        // If we didn't get two coordinates (X and Y), Error out.
        if (count($tmp) != 2) {
        	Error('Invalid query string.');
        } else {
        	// Assign the appropriate variables to the coordinates.
            $xval = $tmp[0];
        	// Ugly hack to fix for PHP's URL rewriter. Searches for a
            // questionmark in the X coordinate and if it finds one,  considers
            // everything AFTER the questionmark as the true X coordinate value.
        	$position = strpos($xval,'?');
        	if ($position !== FALSE) {
        		$xval = substr($xval,($position + 1));
        	}
        	// End Hack.
            $yval = $tmp[1];
        }
    }
	// Generate a random string to make browsers request a fresh copy of the
    // captcha every time the site is loaded. This works better than cache
    // headers which can easily get ignored.
	$random = md5(time());
	global $font_names;
	// Sort the font name list alphabetically, while maintaining the index
	// association.
	asort($font_names);
	// Create an empty string.
	$fonts = '';
	foreach ($font_names as $k => $v) {
		// Loop through the fontnames array and add option values for the HTML
		// template file.
		$fonts .= '<option value="'. $k .'">'. $v .'</option>';
	}
	// Create a captcha code. (5 random numbers)
	$_SESSION['code'] = rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
    // Load and display the post template.
    require_once('template/post.htm');
?>
