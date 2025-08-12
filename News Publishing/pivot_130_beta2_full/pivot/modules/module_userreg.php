<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under 
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// don't access directly..
if(!defined('INPIVOT')){ ('not in pivot'); }

// lamer protection
if (strpos($pivot_path,"ttp://")>0) {	die('no');}
$scriptname = basename((isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['PHP_SELF']);
$checkvars = array_merge($_GET , $_POST, $_SERVER, $_COOKIE);
if ( (isset($checkvars['pivot_url'])) || (isset($checkvars['log_url'])) || (isset($checkvars['pivot_path'])) ) {
	die('no');
}
// end lamer protection



function is_user($name) {

	$name_md5 = strtolower(md5(strtolower($name))); 
	
	if (file_exists('db/users/'.$name_md5.'.php')) {
		return TRUE;	
	} else {
		return FALSE;
	}
	
}



function reg_user($user) {
	global $Cfg;

	$name_md5 = strtolower(md5(strtolower($user['name']))); 
	
	if (save_serialize('db/users/'.$name_md5.'.php', $user)) {	
		echo "User stored!<br /><br />";	
	} else {	
		echo "Could not store new user!!<br /><br />";	
	}
	
	$self= "http://".$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	
	$mail = "You have registered as a user on Pivot '%s'\n\n";
	$mail .= "To verify your account, click the following link:\n %s?func=verify&name=%s&code=%s";
	
	$mail = sprintf($mail, $Cfg['sitename'], $self, urlencode($user['name']), md5($user['pass']."email") );

	if (!mail($user['email'], "[Pivot] Registration confirmation", $mail, "From: ".$user['email'])) {
		echo "<br />". nl2br($mail) ."<br />";
	}
	
	echo "Mail verification sent to ".$user['email'].". Please check your email in a minute to confirm your account.";
	
}


function mail_pass($user) {
	global $Cfg;


	$self= "http://".$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	
	$mail = lang('comment_user', 'forgotten_pass_mail');
	
	$mail = str_replace("%site%", $Cfg['sitename'], $mail);
	$mail = str_replace("%pass%", $user['pass'], $mail);
	$mail = str_replace("%link%", $self, $mail);


	if (!mail($user['email'], "[Pivot] Lost Password", $mail, "From: ".$user['email'])) {
		echo "<br />". nl2br($mail) ."<br />";
	}

	
}

function load_user($name) {
	
	$name_md5 = strtolower(md5(strtolower($name))); 
	
	if (is_user($name)) {
		$user = load_serialize('db/users/'.$name_md5.'.php');
		return $user;
	} else {
		return FALSE;
	}
	
}


function check_user_hash($name,$hash) {	
	
	$name_md5 = strtolower(md5(strtolower($name))); 
	
	if (is_user($name)) {
		$user = load_serialize('db/users/'.$name_md5.'.php');
		return (md5($user['pass']) == $hash);
	} else {
		return FALSE;
	}
	
}

function save_user($user) {
	global $Cfg;

	$name_md5 = strtolower(md5(strtolower($user['name']))); 
	
	if (save_serialize('db/users/'.$name_md5.'.php', $user)) {
		
		// echo "User stored!<br /><br />";
		
	} else {
		
		echo "Could not store user!!<br /><br />";	
	}
		
}

?>
