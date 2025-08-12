<?php
	// What is the full URL to ScribbleBoard? Leave off the file name in the end.
	define('URL_TO_BOARD','');
	// What is the local file system path to the script? If you don't know it,
	// try setting this to './'
	define('SCRIPT_PATH', '');
	// If your webhost does not support file locking (such as free.fr), set the
	// following constats value to TRUE. If you have a loaded site, setting this
	// to TRUE could possibly result in data loss!
	define('NO_FLOCK',FALSE);
    // Define how long people need to wait before to make another post. It might
    // not be 100% bullet-proof but it should be sufficient to stop the casual
    // troll from causing too much damage.
	define('NEXT_POST_TIMEOUT', 300); // 5 Minutes.
	// If you want to be notified by E-Mail when someone posts a message, simply
	// enter your E-Mail address below. (Your server needs to support the mail()
	// command!) This is also where cought insults are mailed to. Leave it blank
	// if you do not want notification.
	define('EMAIL_NEW_MSG_TO', '');
	// Word filter. If you want to filter out certain words, enter them into the
	// array below. How to add new words should be somewhat obvious. If not, all
	// you need to do is copy the last line before ");", paste it directly under
	// it, and change the word. (This is CaSe InSeNsItIvE!)
	$badwords = array(
                      'suck',
                      'blow',
                      'gay',
                      'idiot',
                      'lame',
                      'fuck',
                      'nigger',
                      'nigga',
                      'nerd',
                      'jew',
                      'nazi',
                      'dick',
                      'retard',
                      'dumb',
                      'fag',
                      'ass',
                      'penis',
                      'elite',
                      'viagra',
                      'cialis',
                      'soft-tabs',
                      'casino',
                     );
    // Use the bad word filter? Set this to FALSE if you do not want to use bad
    // word filtering.
    define('USE_BADWORDS', TRUE);
    // Block similar messages. You can block messages that are X% similar to
    // the last Y messages. This further stops flooding from bots which always
    // post the same message. (The median percentage is taken from Y messages
    // and then checked against X%. If it is greater than or equal to X%, the
    // message is rejected.)
    define('BLOCK_PERCENT',60); // X%
    define('BLOCK_MSGNR', 3);   // Y messages
    // Use similar message blocking? Set this to FALSE if you do not want to
    // block similar messages.
    define('USE_BLOCK', TRUE);
	// Where do you want to store the message text and log file? You can change
	// the path here so it's not readable by everyone on the internet. You can
	// use an absolute (/foo/bar/baz/) or a relative (../../baz/) path. If you
	// want to store everything in the current directory, leave this blank.
	define('STORE_PATH','./'); // Script directory.
	// If you want to use a different background image, you can set it here.
	// Be sure to copy this image into the 'images' folder so the script finds
	// it. Feel free to try out 'squaredpaper.gif' if you want. :)
	define('BACKGORUND_IMAGE','linedpaper.gif');
	// It is possible to define the proportions of your ScribbleBoard! Set the
	// height and width values below to something that suits you, or just leave
	// them alone.
	define('BOARD_HEIGHT',480);
    define('BOARD_WIDTH',660);
?>
