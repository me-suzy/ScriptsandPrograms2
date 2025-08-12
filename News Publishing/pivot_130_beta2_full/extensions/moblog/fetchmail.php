<?php

$basedir = dirname(__FILE__);

include_once($basedir."/moblog_lib.php");

ob_start();

// First we check if the interval has passed, so we can check the mailbox
$skip = false;

if (file_exists($pivot_path."db/moblogtimer.txt")) {
	$timer = implode("",file($pivot_path."db/moblogtimer.txt"));
	$diff = mktime() - $timer;
	$messages[] = "[diff is: $diff]";

	if ($diff < $moblog_cfg['interval']) {
		$skip = true;
	}

} else {
	$messages[] = "No timer yet: ".$pivot_path."db/moblogtimer.txt";
}


// If skip is not true, we fetch mail..
if (!$skip) {

	$messages[] =  "Checking email..";

	// Create the class

	$pop3 = new Net_POP3();

	if(PEAR::isError( $ret= $pop3->connect($host , $port ) )){
		echo "Moblog: error connecting: " . $ret->getMessage() . "\n";
		exit();
	}


	if(PEAR::isError( $ret= $pop3->login($user , $pass,'USER' ) )){
		echo "Moblog: error logging in: " . $ret->getMessage() . "\n";

		exit();
	}



	chdir(__WEBLOG_ROOT . "/pivot/");
	require_once("pv_core.php");

	$regen = false;

	// First we fetch the list of available emails..
	$listing = $pop3->getListing();

	// Then we iterate through the list..
	foreach ($listing as $list_item) {

		$email = $pop3->getMsg( $list_item['msg_id'] );

		$messages[] = "fetched mail " .$list_item['msg_id'];

		if (!$moblog_cfg['leave_on_server']) {
			$pop3->deleteMsg( $list_item['msg_id'] );
			$messages[] = "Message was deleted from the server..";
		} else {
			$messages[] = "Message was left on the server..";
		}

		// Perhaps save a local copy..
		if($moblog_cfg['save_mail']) {
			$filename = __MOBLOG_ROOT.'/mail/' . date("Ymd-His") . "-" . $list_item['msg_id'] . ".eml";
			if ($fp = fopen( $filename, "w" )) {
				fwrite($fp, $email);
				$messages[] = "Local copy saved as: $filename";
			} else {
				$messages[] = "Alas! Woe is me! I couldn't save a local copy.";
			}

		}

		$entry = array();

		// Parse and post the email..
		parse_email($email);

		compose_entry();

		$regen = true;

	}

	$pop3->disconnect();

	if ($regen) {

		debug_printr($Paths);
		debug("pivot_path: $pivot_path");

		// regenerate entry, frontpage and archive..
		generate_pages($Pivot_Vars['piv_code'], TRUE, TRUE, TRUE);
		$messages[] = "Rebuilt the weblog's frontpage..";
	}





	$fp = fopen($pivot_path."db/moblogtimer.txt", "wb");
	fwrite($fp, mktime());
	fclose($fp);


}



foreach($messages as $message) {
	echo $message."\n";
}

echo "Done!";

$buffer = ob_get_clean();

$fp = fopen($pivot_path."db/mobloglog.txt", "ab");

fwrite($fp, date("Y-m-d H:i:s")."\n");
fwrite($fp, $buffer);
fwrite($fp, "\n\n--------------------------\n\n");

fclose($fp);

?>
