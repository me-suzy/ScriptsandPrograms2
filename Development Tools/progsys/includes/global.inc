<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
if(isset($dosafephp) && ($dosafephp))
{
	require_once($path_progsys.'/includes/safephp.inc');
	safe_php();
}
if(!isset($do_db_die))
	$do_db_die=true;
if(!isset($no_rgcheck))
	$no_rgcheck=false;
$dbinited=false;
$typerfontstyles=array("plain","bold","italic","bolditalic");
$err_query      = '<br><br><br><br><br><font size=+1>An error occured</font><hr>ProgSys was unable to connect'.
	  ' to the database server.<BR>Please try again later.<BR>';
$version ="0.148";
$psprogname = "ProgSys";
$pspurl = "http://www.boesch-it.de";
$copyright_note = "&copy;2002-2005 B&ouml;sch EDV-Consulting";
$copyright_url = "<a class=\"copyright\" target=\"_blank\" href=\"$pspurl\">$psprogname</a> V$version";
$copyright_asc = "(c)2002-2005 by Boesch EDV-Consulting";
// Set the error reporting to a sane value:
if(!$testmode)
	error_reporting  (E_ERROR | E_WARNING | E_PARSE);

if(!$no_rgcheck)
{
	$rg_ini=@ini_get('register_globals');
	if($rg_ini || (strtolower($rg_ini)=='on'))
		die("register_globals should be set to off for security reasons");
}
if(!$db = @mysql_connect($dbhost,$dbuser,$dbpasswd))
{
	if($do_db_die)
		die($err_query);
}
else
{
	if(!@mysql_select_db($dbname,$db))
	{
		if($do_db_die)
			die($err_query);
	}
	else
		$dbinited=true;
}

if(!isset($new_global_handling))
{
	if(phpversion() >= '4.1.0')
		$new_global_handling=true;
	else
		$new_global_handling=false;
}

if(!isset($cookiedomain) || !$cookiedomain)
{
	if($new_global_handling)
		$myhost=$_SERVER["HTTP_HOST"];
	else
		$myhost=$HTTP_HOST;
	$portpos=strpos($myhost,":");
	if($portpos>0)
		$myhost=substr($myhost,0,$portpos);
	$cookiedomain = $myhost;
	if(strpos($cookiedomain,".")<1)
		$cookiedomain="";
	else
	{
		$num_points=substr_count($myhost,".");
		if($num_points<2)
			$cookiedomain=".".$myhost;
	}
}

if(!isset($upload_avail))
{
	$upload_avail=true;
	$upload_ini=@ini_get('file_uploads');
	if(( $upload_ini == '0') || !$upload_ini)
		$upload_avail=false;
	if(strtolower($upload_ini) == 'off')
		$upload_avail=false;
	if(phpversion() == '4.0.4pl1')
		$upload_avail=false;
	if((phpversion() < '4.0.3') && (@ini_get('open_basedir') != ''))
		$upload_avail=false;
}

if(!isset($gdavail))
{
	if(extension_loaded("gd"))
		$gdavail=true;
	else
		$gdavail=false;
}

if($new_global_handling)
	$act_script_url=$_SERVER["PHP_SELF"];
else
	$act_script_url=$PHP_SELF;

if(phpversion() < '4.1.0')
{
	$_POST    = &$HTTP_POST_VARS;
	$_GET     = &$HTTP_GET_VARS;
	$_COOKIE  = &$HTTP_COOKIE_VARS;
	$_REQUEST = array_merge($HTTP_COOKIE_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS);
}

while( list($var, $param) = @each($_POST) )
{
	if(!isset($$var))
	{
		$$var=$_POST[$var];
		if(!get_magic_quotes_gpc())
			$$var=addslashes($$var);
	}

}
while( list($var, $param) = @each($_GET) )
{
	if(!isset($$var))
	{
		$$var=$_GET[$var];
		if(!get_magic_quotes_gpc())
			$$var=addslashes($$var);
	}
}
if(!isset($REMOTE_ADDR))
{
	if($new_global_handling)
		$REMOTE_ADDR=$_SERVER["REMOTE_ADDR"];
}
if(!isset($HTTP_USER_AGENT))
{
	if($new_global_handling)
		$HTTP_USER_AGENT=$_SERVER["HTTP_USER_AGENT"];
}
if(!isset($HTTP_REFERER))
{
	if($new_global_handling)
		@$HTTP_REFERER=$_SERVER['HTTP_REFERER'];
	else
		@$HTTP_REFERER=$HTTP_SERVER_VARS["HTTP_REFERER"];
}
require_once($path_progsys."/includes/timezones.inc");
?>