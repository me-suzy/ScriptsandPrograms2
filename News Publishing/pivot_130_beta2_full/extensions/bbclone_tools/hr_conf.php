<?php

// Hardened referrers version 0.4-modified
// By Marco van Hylckama Vlieg, 
// marco@i-marco.nl
// 
// Some changes by Bob den Otter, bob@pivotlog.net
//
// Change the values below according to your situation (your domain)
// and enter your own secret key.

$refkeydir = dirname(dirname(dirname(__FILE__)))."/pivot/db/refkeys";
hr_makedir($refkeydir);

define (__SECRET__, check_salt());
define (__MYDOMAIN__, $_SERVER['HTTP_HOST']);



// an easy function to recursively create chmodded directory's
function hr_makedir($name) {

	// if it exists, just return.
	if (file_exists($name)) {
		return;
	}

	// if more than one level, try parent first..
	if (dirname($name) != ".") {
		hr_makedir(dirname($name));
	}

	$oldumask = umask(0);
	@mkdir ($name, 0777);
	@chmod ($name, 0777);
	umask($oldumask);

}


/**
 * Check if the salt is defined
 */
function check_salt() {
	global $refkeydir;
	
	if(file_exists($refkeydir."/salt.php")) {
		include_once($refkeydir."/salt.php");
	} else {
		$salt = make_getrefkey();
		$fp = fopen($refkeydir."/salt.php", "w");
		fwrite($fp, sprintf('<?php $salt="%s"; ?>', $salt));
	}
	
}
	

/**
 * Make a getrefkey
 */
function make_getrefkey() {
	
	$sid="";
	for ($i = 1 ; $i <= 20; $i++) {
		$rchar = mt_rand(1,30);
		if($rchar <= 10) {
			$sid .= chr(mt_rand(65,90));
		}elseif($rchar <= 20) {
			$sid .= mt_rand(0,9);
		}else{
			$sid .= chr(mt_rand(97,122));
		}
	}	
	return $sid;
}


?>
