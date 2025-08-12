<?php
	require_once('init.php');
	// Do we have a message? If the text field was left blank, most browsers dont
	// bother transfering it, but some browsers are dumb and still do it; thus
	// we need to check if the variable is set and if its not empty.
    if (!isset($_POST['message']) || empty($_POST['message'])) {
        Error('Please enter a message.');
    }
    // Are we filtering bad words?
    if (USE_BADWORDS) {
        global $badwords;
        // Loop through the bad words list and check if we get a match.
        foreach ($badwords as $v) {
            if (strpos(strtoupper($_POST['message']), strtoupper($v)) !== FALSE) {
                // We got a match. If we are going to need to send an E-Mail to
                // the admin, we prepare the string to send an send it off.
                if (defined('EMAIL_NEW_MSG_TO') && EMAIL_NEW_MSG_TO != '') {
                    $tmp = "Date: ". date('r') . "\r\nIP: ". $_SERVER['REMOTE_ADDR'] ." (". gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")\r\n Message: ". stripslashes($_POST['message']) ."\r\n X,Y: ". $_POST['xval'] .",". $_POST['yval'] ."\r\n\r\n";
                    mail(EMAIL_NEW_MSG_TO, '[ScribbleBoard] Bad Word Detected', $tmp);
                }
                // This will make the user/roll have to wait a while before he
                // or she can post a new message.
                $_SESSION['lastpost'] = time();
                // Show a vague error message to hide the fact that the user/
                // troll was cought by the bad word filter.
                Error('Your message could not be added because of its contents.');
            }
        }
    }
    // Check for similarity. If the message is too similar, we will show an
    // equally vague error as above for identical reasons.
    If (USE_BLOCK && MessageIsTooSimilar($_POST['message'])) {
        // Show the error.
        Error('Your message is too similar to previous ones.');
    }
    // Did the captcha get filled out correctly? Was it filled out at all? If not,
    // deny the post.
    if (!isset($_POST['captcha']) || ($_POST['captcha'] != $_SESSION['code'])) {
    	Error('Captcha code entered did not match the one in the image.');
    } else {
    	// So it did match, then we need to overwrite it with randomness now.');
    	$_SESSION['code'] = md5(time());
    	// Also set the time the last post was made.
    	$_SESSION['lastpost'] = time();
    }
    // These should in general *never* evaluate to FALSE (well, TRUE because the
    // exclamation mark reverses the boolean) because they are not text fields
    // or something that doesn't have a value by default.
    if (!isset($_POST['font']) || !isset($_POST['size']) || !isset($_POST['textcolor']) || !isset($_POST['degrees']) || !isset($_POST['xval']) || !isset($_POST['yval'])) {
    	Error('Required POST data is missing.');
    }
    // Create the entry for message text file. Each record is on its own line,
    // and fields are being seperated with nullchars.
    $tmp = stripslashes($_POST['message']) .chr(0). $_POST['font'] .chr(0). $_POST['size'] .chr(0). $_POST['textcolor']  .chr(0). $_POST['degrees'] .chr(0). $_POST['xval'] .chr(0). $_POST['yval'] .chr(0) ."\r\n";
    // Open the mssage text file for appending. If it doesn't exist, try to
    // create it.
    $fp = fopen(STORE_PATH. '/messages.txt','a+');
	// If file locking is not disabled, try to lock the file five times. If that
	// fails for whatever reason, give up and error out. We are using an exclusive
	// lock here by the way, which will prevent other programs or instances of
	// ScribbleBoard from reading the file while it's being written to. This is
	// important because reading while writing might lead to data corruption.
	if (!NO_FLOCK) {
	    $cnt = 0;
	    while (!flock($fp,LOCK_EX)) {
	        if ($cnt = 4) {
	            fclose($fp);
	            Error('Unable to get a exclusive lock for &quot;messages.txt&quot; after 5 tries.');
	        } else {
	            $cnt++;
	            sleep(1);
	        }
	    }
	}
    // Write the message string to the message text file.
    fwrite($fp,$tmp);
    // If file locking is not disabled, remove the lock.
	if (!NO_FLOCK) {
	    flock($fp,LOCK_UN);
	}
    // Close our message text file since we're done with it.
    fclose($fp);
    // Create the entry for message log file. This is a "human readable" text
    // file which contains the IP address, along with the hostname it resolves
    // to and the message that was posted from it.
	$tmp = "Date: ". date('r') . "\r\nIP: ". $_SERVER['REMOTE_ADDR'] ." (". gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")\r\n Message: ". stripslashes($_POST['message']) ."\r\n X,Y: ". $_POST['xval'] .",". $_POST['yval'] ."\r\n\r\n";
    // Open the mssage log file for appending. If it doesn't exist, try to create
    // it.
    $fp = fopen(STORE_PATH. '/messages.log','a+');
	// If file locking is not disabled, try to lock the file five times. If that
	// fails for whatever reason, give up and error out. We are using an exclusive
	// lock here by the way, which will prevent other programs or instances of
	// ScribbleBoard from reading the file while it's being written to. This is
	// important because reading while writing might lead to data corruption.
    if (!NO_FLOCK) {
        $cnt = 0;
        while (!flock($fp,LOCK_EX)) {
            if ($cnt = 4) {
                fclose($fp);
                Error('Unable to get a exclusive lock for &quot;messages.log&quot; after 5 tries.');
            } else {
                $cnt++;
                sleep(1);
            }
        }
    }
    // Write the message log string to the message log file.
    fwrite($fp,$tmp);
    // If file locking is not disabled, remove the lock.
	if (!NO_FLOCK) {
	    flock($fp,LOCK_UN);
	}
	// If E-Mail is enabled, send a message.
	if (defined('EMAIL_NEW_MSG_TO') && EMAIL_NEW_MSG_TO != '') {
	   mail(EMAIL_NEW_MSG_TO, '[ScribbleBoard] Message Added', $tmp);
    }
    // Close our message log file since we're done with it.
    fclose($fp);
    // Redirect the user to the index page where they will see their message. :)
    header('Location: index.php');
?>
