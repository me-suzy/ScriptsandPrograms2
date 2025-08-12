<?php

/* Rewrite of fetchmail.php using the imap extension to PHP via
   mail.class.php (borrowed from Issue-Tracker).
  
   Hacked by Hans Fredrik Nordhaug (18.05.2005) - "hansfn" in the Pivot forum.
*/


/* *** CONFIG:
   Set the protocol and secure type - should be included in config.inc.php */
$protocol = 'pop3'; # Type of server (imap or pop3)
$secure = TRUE;     # Enabling this if server is using SSL (as gmail)
$mailbox = '';      # Mailbox name to connect to (if protocol is imap)
/* *** END CONFIG *** */


$basedir = dirname(__FILE__);

require_once($basedir."/moblog_lib.php");
require_once($basedir."/mail.class.php");


$server = array(
    'type'      => $protocol,                   # Type of server (imap or pop3)
    'server'    => $moblog_cfg['pop_host'],     # Server to connect to
    'secure'    => $secure,                     # Enabling this if server is using SSL
    'mailbox'   => $mailbox,                    # Mailbox name to connect to
    'username'  => $moblog_cfg['pop_user'],     # Username to use
    'password'  => $moblog_cfg['pop_pass']      # Password to use
        );

// ob_start();
 
// First we check if the interval has passed, so we can check the mailbox
$skip = false;

if (file_exists($pivot_path."db/moblogtimer.txt")) {
	$timer = implode("",file($pivot_path."db/moblogtimer.txt"));
	$diff = mktime() - $timer;
	$log[] = "[diff is: $diff]";
	
	if ($diff < $moblog_cfg['interval']) {
		$skip = true;
	}
	
} else {
	$log[] = "No timer yet: ".$pivot_path."db/moblogtimer.txt";	
}

// If skip is not true, we fetch mail..
if (!$skip) {
	$log[] =  "Checking email..";
		
        $mail = new Mail;
        if (!$mail->connect($server)) {
            echo "Moblog: No connection.\n";
            exit();
        } else {
            $log[] = "Moblog: OK connection.\n";
        }
	
	chdir(__WEBLOG_ROOT . "/pivot/");
	require_once("pv_core.php");
	
	$regen = false;
	
	// First we fetch the list of available emails..
        $mail->parse_messages();

        if (is_array($mail->messages)) {
            foreach ($mail->messages as $message) {	
       		$log[] = "fetched mail " .$message['message_id'];
	
		if (!$moblog_cfg['leave_on_server']) {
			$mail->delete($message['msgno']);
			$log[] = "Message was deleted from the server..";
		} else {
			$log[] = "Message was left on the server (if supported)..";
		}
	
		// Perhaps save a local copy..
		if($moblog_cfg['save_mail']) {
			$filename = __MOBLOG_ROOT.'/mail/' . date("Ymd-His") .  "-" . $message['message_id'] . ".eml";
			if ($fp = fopen( $filename, "w" )) {
				fwrite($fp, $message['rawdata']);
				$log[] = "Local copy saved as: $filename";
			} else {
				$log[] = "Alas! Woe is me! I couldn't save a local copy.";
			}
	
		}
	
		$entry = array();
	
		// Parse and post the email..
		parse_email($message['rawdata']);
		
	        compose_entry();
            }
            $regen = true;
        } else {
            $log[] = "No email on server.";
        }
	
        $mail->close();
	
	if ($regen) {
		
		debug_printr($Paths);
		debug("pivot_path: $pivot_path");

		// regenerate entry, frontpage and archive..
		generate_pages($Pivot_Vars['piv_code'], TRUE, TRUE, TRUE);	
		$log[] = "Rebuilt the weblog's frontpage..";
	}
	
	
	$fp = fopen($pivot_path."db/moblogtimer.txt", "wb");
	fwrite($fp, mktime());
	fclose($fp);
		
	
}


	
foreach($log as $logentry) {
	echo $logentry."<br>";	
}

echo "Done!";

$buffer = ob_get_clean();

$fp = fopen($pivot_path."db/mobloglog.txt", "ab");

fwrite($fp, date("Y-m-d H:i:s")."\n");
fwrite($fp, $buffer);
fwrite($fp, "\n\n--------------------------\n\n");

fclose($fp);

?>
