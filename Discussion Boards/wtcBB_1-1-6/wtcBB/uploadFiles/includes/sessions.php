<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FRONT END - GLOBAL\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// ##### !!!!! IMPORTANT !!!!! ##### \\
// ##### THIS FILE SHOULD BE   ##### \\
// ##### INCLUDED WHEREVER     ##### \\
// ##### YOU START A NEW PAGE  ##### \\
// ##### FOLLOW THE PATTERNS   ##### \\
// ##### SET IN THE FILES      ##### \\
// ##### !!!!! IMPORTANT !!!!! ##### \\

// once this file is included.. 
// we should have access to all 
// information needed in this file

// the location and action should already be 
// provided to us.. right before the 
// including of this file
// create session array
$session = Array(
	"location" => $sessionInclude['location'],
	"action" => $sessionInclude['action'],
	"title" => $sessionInclude['title'],
	"last_activity" => time(),
	"ip_address" => $_SERVER['REMOTE_ADDR'],
	"user_agent" => $_SERVER['HTTP_USER_AGENT'],
	"userid" => $userinfo['userid'],
	"username" => $userinfo['username'],
	"sessionid" => SESSIONID // this should be unqiue
	);

// update
if($bboptions['cookie_timeout']) {
	query("DELETE FROM sessions WHERE last_activity <= '" . (time() - $bboptions['cookie_timeout']) . "' OR (username = '".$userinfo['username']."' AND userid != 0)");
}

query("REPLACE INTO sessions (location,action,title,last_activity,ip_address,user_agent,userid,username,sessionid) VALUES ('".addslashes($session['location'])."','".addslashes($session['action'])."','".addslashes($session['title'])."','".$session['last_activity']."','".$session['ip_address']."','".$session['user_agent']."','".$session['userid']."','".$session['username']."','".SESSIONID."')");

// check recordinfo
if($bboptions['record_num'] <= count($sessArr) AND count($sessArr) > 0) {
	// update record!
	query("UPDATE wtcBBoptions SET record_num = '".count($sessArr)."' , record_date = '".time()."'");
}

// only if userid isn't 0...
// 0 is guest if you didn't know!
if($session['userid']) {
	// if there are no rows.. insert a new one
	if(!$userinfo['counting']) {
		// insert
		query("INSERT INTO logged_ips (username,userid,ip_address) VALUES ('".$session['username']."','".$session['userid']."','".$session['ip_address']."')");
	}

	// update userinfo ip address..
	if($userinfo['user_ip_address'] != $session['ip_address']) {
		query("UPDATE user_info SET user_ip_address = '".$session['ip_address']."' WHERE userid = '".$session['userid']."'");
	}
}

?>