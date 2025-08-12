<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /m.php
|
|	Version: >>v2.1.0<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

if(file_exists('/home/example/ds_files/scripts/ds_config.php')) {include_once('/home/example/ds_files/scripts/ds_config.php');}
else {echo 'Config file missing, please re-install.';exit;}


if(DS_CTOKENON ==1 || DS_STOKENON ==1) 
{
	session_start();
}

/* use a browser cookie to disable link sharing between browsers */
if(DS_CTOKENON == 1) 
{
	$ctoken = md5(uniqid(rand(), true));
	setcookie('ctoken', $ctoken, time()+DS_ACTIVEDL);
	define('CTOKEN', $ctoken);
}

/* set up session token to help prevent offsite robots from leeching. */
if(DS_STOKENON == 1)
{
	session_start();
	$stoken = md5(uniqid(rand(), true));
	$_SESSION['stoken'] = $stoken;
	define('STOKEN', $stoken);
}

function make_token() 
{
	$currhour = date('Ymd');
	$token['time'] = time();
	$token['hash'] = sha1(DS_FTOKEN.$currhour);
	if(DS_CTOKENON ==1) $token['ctoken'] = CTOKEN;
	if(DS_STOKENON ==1) $token['stoken'] = STOKEN;
	$passtoken = base64_encode(serialize($token));
	return $passtoken;
}
 
$token = make_token();

if(preg_match('/^([A-Za-z0-9._\-\/:\s]{1,164})$/', stripslashes($_GET['p']), $matches)) {$p = $matches[0];}else{echo 'Invalid File Name!';exit;}
//header("Refresh: 5; URL=http://example/dsplus/ds.php?p=$p&t=$token"); // using this causes http referer variable to be lost (in the logs), so it is off by default.

$path = $_SERVER['DOCUMENT_ROOT'];
$file = $path.'/dsplus/m.html';
$data = file_get_contents($file);

$replace = str_replace ("<filename />", "$p", $data);
$replace = str_replace ("<token />", "$token", $replace);

echo $replace;
?>