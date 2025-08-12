<?php

############################################################################
############################################################################
##                                                                        ##
## This script is copyright Rupe Parnell (Starsol.co.uk) 2003 - 2005.     ##
##                                                                        ##
## Distribution of this file, and/or any other files in this package, via ##
## any means, withour prior written consent of the author is prohibited.  ##
##                                                                        ##
## Starsol.co.uk takes no responsibility for any damages caused by the    ##
## usage of this script, and does not guarantee compability with all      ##
## servers.                                                               ##
##                                                                        ##
## Please use the contact form at                                         ##
## http://www.starsol.co.uk/support.php if you need any help or have      ##
## any questions about this script.                                       ##
##                                                                        ##
############################################################################
############################################################################

require_once('faq-variables.php');

$spiders = array('alexa','architextspider','googlebot','inktomi','msnbot','overture','scooter','slurp','teoma','yahoo'); // Enter part of a spiders HTTP user agent in this array to disallow it from accidentally rating FAQs as useful and/or not useful. All lower case.
$banned_ips = array('216.194.68.195'); // Spiders (or any problem users) can also be banned from rating FAQs by entering their IP address in this array.

// No need to edit the next three
$product = 'FAQ Manager';
$version = '2.0';
$debug = FALSE;

if (!function_exists('connect_to_mysql')){
	function connect_to_mysql(){

		global $db_location, $db_username, $db_password, $db_database;

		$conn = mysql_connect($db_location,$db_username,$db_password); 
		if (!$conn) deal_with_mysql_error('MySQL Database Connection Error.  '.mysql_error(),'clean'); 
		mysql_select_db($db_database,$conn) or deal_with_mysql_error('MySQL Database Selection Error.  '.mysql_error(),'clean');

		return;
	}
}

if (!function_exists('error_message')){
	function error_message($text){
		echo'<p class="errortext"><br /><span style="background-color: #FF6666; font-weight: bold;">Error:</span><br />'.$text.'</p>';
		return;
	}
}

function deal_with_mysql_error($info,$where='clean'){

	echo'<p>Sorry, an error occured.<br /><span class="smallprint">'.$info.'</span></p>';

	if ($where == "clean"){
		echo'<p>Follow <a href="javascript:history.go(-1)">this link</a> to go back the page you were previously looking at.</p>';
	} else {
		echo'<p>Follow <a href="javascript:history.go(-1)">this link</a> to go back the page you were previously looking at.</p>';
		admin_footer();
	}

	@mysql_close();
	exit;

	return;
}


function admin_header(){

	global $site_name, $site_domain, $site_url, $admin_email, $product, $version;

	echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n\n".'<html>'."\n".'<head>'."\n".'<title>'.$site_name.' Admin Area - Powered by Starsol.co.uk</title>'."\n".'<meta name="robots" content="noindex, nofollow" />'."\n".'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />'."\n".'<link rel="stylesheet" href="faq-stylesheet-admin.css" type="text/css" />'."\n".'</head>'."\n\n";

	echo'<body>'."\n\n".'<map name="csm" id="csm"><area href="http://www.starsol.co.uk/support.php?email='.urlencode($admin_email).'&amp;domain='.urlencode($site_domain).'&amp;url='.urlencode($site_url).'&amp;product='.urlencode($product.' '.$version).'" shape="rect" coords="231, 58, 359, 79" target="_blank" alt="Contact Support" /></map>'."\n\n".'<div class="head"><img src="http://www.starsol.co.uk/images/aaf/powered-by-starsol.jpg" width="360" height="80" border="0" alt="Powered by Starsol.co.uk" usemap="csm" /></div>'."\n\n".'<div class="main"><div style="margin: 5px;">';

	return;
}

function admin_footer(){

	echo'</div></div>'."\n\n".'<div class="foot"><a href="http://www.starsol.co.uk/" target="_blank"><img src="http://www.starsol.co.uk/images/aaf/copyright.jpg" width="170" height="30" border="0" alt="Copyright Rupe Parnell trading as Starsol.co.uk" /></a></div>'."\n\n".'</body>'."\n".'</html>';

	return;

}

function admin_login_check($input_u,$input_p){

	global $admin_username, $admin_password;

	if ($input_u == $admin_username AND $input_p == md5($admin_password)){
		return TRUE;
	} else {
		return FALSE;
	}	

}


function admin_login_form(){

	global $site_name;

	echo'<h1>Please Login</h1>'."\n\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="process_login" />'."\n".'Username: <input type="text" name="admin_u" /><br />'."\n".'Password: <input type="password" name="admin_p" /><br /><br />'."\n".'<input type="submit" value="Login to '.$site_name.' Admin Area" />'."\n".'</form>';

	return;

}

function admin_faq_links($showindex='1'){

	echo'<p><b>FAQs:</b><br />';	
	if ($showindex){
		echo'<a href="'.$_SERVER[PHP_SELF].'?t=index">Index</a> | ';
	}
	echo'<a href="'.$_SERVER[PHP_SELF].'?t=cat_new">Add Category</a> | <a href="'.$_SERVER[PHP_SELF].'?t=cat_list">Category List</a> | <a href="'.$_SERVER[PHP_SELF].'?t=faq_new">Add Question</a> | <a href="'.$_SERVER[PHP_SELF].'?t=faq_list">Question List</a>';
	if ($showindex){
		echo' | <a href="'.$_SERVER[PHP_SELF].'?t=logout">Log Out</a>';
	}
	echo'</p>'."\n\n";
	return;

}

function admin_misc_links($showindex='1'){

	echo'<p><b>Misc:</b><br />';
	if ($showindex){
		echo'<a href="'.$_SERVER[PHP_SELF].'?t=index">Index</a> | ';
	}
	echo'<a href="'.$_SERVER[PHP_SELF].'?t=settings_edit">View/Edit Settings</a> | <a href="'.$_SERVER[PHP_SELF].'?t=ratings">Ratings Tools</a> | <a href="'.$_SERVER[PHP_SELF].'?t=version">Version Information</a> | <a href="'.$_SERVER[PHP_SELF].'?t=support">Contact Technical Support</a>';
	if ($showindex){
		echo' | <a href="'.$_SERVER[PHP_SELF].'?t=logout">Log Out</a>';
	}	
	echo'</p>'."\n\n";
	return;

}

function count_faq($uin){

	global $db_prefix;

	$hf = @mysql_num_rows(mysql_query('SELECT uin FROM '.$db_prefix.'ratings WHERE qu="'.$uin.'" AND rating="1"')) or $hf='0';
	$nhf = @mysql_num_rows(mysql_query('SELECT uin FROM '.$db_prefix.'ratings WHERE qu="'.$uin.'" AND rating="0"')) or $nhf='0';

	$rc = $hf + $nhf;
	if ($rc){
		$rating = round($hf / $rc * 100);
	} else {
		$rc = '0';
	}

	@mysql_query('UPDATE '.$db_prefix.'q SET rating="'.$rating.'",rc="'.$rc.'" WHERE uin="'.$uin.'"') or deal_with_mysql_error('FAQ Updation MySQL Error (count_faq function). ','clean');

	return array($rating, $rc);

}

function ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content){

	global $site_name, $site_domain;

	if (!$title){
		$title = $site_name.': FAQ';
	}
	if (!$meta_description){
		$meta_description = 'The frequently asked questions area of '.$site_name.' ('.$site_domain.').';
	}
	if (!$meta_keywords){
		$meta_keywords = $site_name.', faq, frequently, asked, questions';
	}
	if (!$meta_robots){
		$meta_robots = 'index, follow';
	}

	$main_content .= '<div style="margin: 3px; font-family: Arial,sans-serif; font-size: 7pt; color: #888888;">'.$site_name.' FAQ powered by <a href="http://www.starsol.co.uk/scripts/" target="_blank">Starsol Scripts</a>.</div>';

	echo str_replace(array("[[[title]]]", "[[[meta_description]]]", "[[[meta_keywords]]]", "[[[meta_robots]]]", "[[[main_content]]]"), array($title, $meta_description, $meta_keywords, $meta_robots, $main_content), file_get_contents('faq-template-global.txt'));
}

?>