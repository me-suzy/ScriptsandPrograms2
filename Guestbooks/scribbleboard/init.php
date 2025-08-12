<?php
	require_once('config.php');
	require_once('functions.php');
	require_once('ttfinfo.php');
	define('VERSION', '1.49');
	global $badwords;
    // First of all, check what fonts we have installed in the 'fonts/' folder.
    $directory = dir(SCRIPT_PATH .'/fonts/');
    // Create an array that will hold the font names, and an array that will
    // hold the filename of the font.
    $font_names = array();
    $font_files = array();
    // Loop through the directory.
    while (FALSE !== ($entry = $directory->read())) {
        if (strpos($entry, '..') === FALSE && strtoupper($entry) != 'CPT.TTF' && strpos($entry, '.') !== 0) {
            // We can only find the name of TTF fonts. :(
            if (strpos(strtoupper($entry), '.TTF') !== FALSE) {
                $ttfname = get_friendly_ttf_name('./fonts/'. $entry);
            } else {
                $ttfname = StripExt($entry);
            }
            $font_names[] = $ttfname;
            $font_files[] = $entry;
        }
    }
    // Close the directory access.
    $directory->close();
    // Check if the constant URL_TO_BOARD was filled out.
    if (URL_TO_BOARD == '') {
		Error('&quot;URL_TO_BOARD&quot; in config.php was left blank!');
	}
    // Check if a post timeout was defined or set.
	if (!defined('NEXT_POST_TIMEOUT') || NEXT_POST_TIMEOUT == '') {
		Error('&quot;NEXT_POST_TIMEOUT&quot; was not defined or set.');
	}
	// Check if the more or less special captcha font file exists.
    if (!file_exists('./fonts/CPT.TTF')) {
        Error('The font captcha font (&quot;CPT.TTF&quot;) does not exist.');
    }
    // Check if the bad word option is turned on, but no bad words were defined.
    if (defined('USE_BADWORDS') && TRUE && count($badwords) == 0) {
        Error('You have set &quot;USE_BADWORDS&quot; to true but not defined any bad words in the &quot;$badwords&quot; array.');
    }
	// Check if the specified background image exists in the 'images' folder.
	if (!file_exists('./images/'. BACKGORUND_IMAGE)) {
		Error('The background image you specified (&quot'. BACKGORUND_IMAGE .'&quot) does not exist.');
	}
	// If there are no message files, we need to create those.
	if (!file_exists(STORE_PATH .'/./messages.txt') || !file_exists(STORE_PATH .'/./messages.log')){
		touch(STORE_PATH .'/./messages.txt');
		touch(STORE_PATH .'/./messages.log');
	}
	// Check if file locking is really supported. We will try two times to
	// ensure that we didn't just fail a race condition.
    $fp = fopen(STORE_PATH .'/messages.txt','r');
    $cnt = 0;
    while (!flock($fp,LOCK_SH)) {
        $cnt++;
        if ($cnt == 1) { // 0,1 = 2 tries! :)
            Error('File locking test failed. You can turn it off in the configuration file if your host does not support it.');
        }
    }
    flock($fp,LOCK_UN);
    fclose($fp);
	// Do we have GD with FreeType2 compiled into this PHP installation? It's
	// a big requirement and the script won't work without it.
	if (!function_exists('imagecreatetruecolor') || !function_exists('imagettftext')) {
		Error('Either GD (>=2.0.1) or the FreeType2 extension to GD does not seem to be enabled in this PHP installation. Please ask your system administrator to enable GD or extend it with FreeType2.');
	}
	// Check if we can antialias the image to make it look better.
	if (function_exists('imageantialias')) {
		define('ANTIALIAS_OK',TRUE);
	} else {
		define('ANTIALIAS_OK',FALSE);
	}
	// Check if we are using E-Mail and the mail() function exists.
	if (!function_exists('mail') && defined('EMAIL_NEW_MSG_TO') && EMAIL_NEW_MSG_TO != '') {
        Error('This PHP installation cannot send E-Mail ("mail()" function is disabled). Please set &quot;EMAIL_NEW_MSG_TO&quot; to a blank value.');
    }
    // It's all good; everything seems OK. ^_^
?>
