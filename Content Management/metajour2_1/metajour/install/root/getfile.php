<?php
require('site.php');
require($system_path.'getfile.php');

	$userhandler =& getUserHandler();
	$user = $userhandler->getSmartyVars();
	$db =& getDbConn();
	$sql = "INSERT DELAYED INTO binfile_statistics 
	(sessionid, userid, timestamp, site, pageid, ip, useragent, browser, 
	version, maj_ver, min_ver, letter_ver, javascript, platform, os, 
	browserlanguage, userlanguage, usercreated, referer, generated)
	VALUES (
	'".session_id()."', 
	'".$_SESSION['usr']['validuserid']."', 
	NOW(), 
	'".$site."', 
	'".$_REQUEST['objectid']."', 
	'".$user['ip']."',
	'".$user['useragent']."', 
	'".$user['browser']."', 
	'".$user['browserversion']."', 
	'".$user['browsermajversion']."',
	'".$user['browserminversion']."', 
	'".$user['browserletterversion']."', 
	'".$user['javascript']."', 
	'".$user['platform']."', 
	'".$user['os']."', 
	'".$user['browserlanguage']."', 
	'".$user['language']."', 
	'".$user['created']."', 
	'".$_SERVER['HTTP_REFERER']."', 
	'0'
	)";
	$db->Execute($sql);
?>