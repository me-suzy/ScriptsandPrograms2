<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function safe_php() {
/**
* Lets verify the PHP configuration.
*/
	$register_globals=@ini_get('register_globals');
/**
* Get super gloabals. We don't need $_SERVER, $_FILES and $_ENV. 
*/
	$superglobals = array('_GET', '_POST', '_COOKIE','_REQUEST'); 
/**
* Now, let's clean it up !
*/ 
	foreach ($superglobals as $superglobal)
	{
		if (is_array($superglobal))
		{
			foreach ($$superglobal as $key => $val)
			{
/**
* Unset global variables GET, POST and COOKIE if register_globals is on.
*/
				if ($register_globals)
				{
					unset($GLOBALS[$key]);
				}
			}
		}
	}
}
// ini_set("display_errors","1");
if(isset($dosafephp) && ($dosafephp))
	safe_php();
if(!isset($do_db_die))
	$do_db_die=true;
if(!isset($ovwglobals))
	$ovwglobals=false;
$dbinited=false;
$typerfontstyles=array("plain","bold","italic","bolditalic");
$err_query      = '<br><br><br><br><br><font size=+1>An error occured</font><hr>SimpNews was unable to connect'.
	  ' to the database server.<BR>Please try again later.<BR>';
$version ="2.28.15";
$backup_version="2.28";
$restore_version="2.28";
$snprogname = "SimpNews";
$snpurl = "http://www.boesch-it.de";
require_once($path_simpnews.'/includes/const.inc');
// Set the error reporting to a sane value:
if(!$testmode)
{
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	if(!isset($noseccheck))
		$noseccheck=false;
}
else
	$noseccheck=true;
if(!isset($no_rgcheck))
	$no_rgcheck=false;
if(!isset($disallowemails))
	$disallowemails=false;
if(!isset($dodebug))
	$dodebug=false;
if(!$no_rgcheck)
{
	$rg_ini=@ini_get('register_globals');
	if($rg_ini || (strtolower($rg_ini)=='on'))
		die("register_globals should be set to off for security reasons");
}
if(!isset($new_global_handling))
{
	if(phpversion() >= '4.1.0')
		$new_global_handling=true;
	else
		$new_global_handling=false;
}

if(!isset($has_file_errors))
{
	if(phpversion() >= '4.2.0')
		$has_file_errors=true;
	else
		$has_file_errors=false;
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
$safemode_ini=@ini_get('safe_mode');
if(( $safemode_ini == '1') || (strtolower($safemode_ini) == 'on'))
	$insafemode=true;
else
	$insafemode=false;
if(!isset($gdavail))
{
	if(extension_loaded("gd"))
		$gdavail=true;
	else
		$gdavail=false;
}
if(!$db = @mysql_connect($dbhost,$dbuser,$dbpasswd))
{
	if($do_db_die)
	{
		echo $err_query;
		if($testmode)
			echo mysql_error();
		exit;
	}
}
else
{
	if(!@mysql_select_db($dbname,$db))
	{
		if($do_db_die)
		{
			echo $err_query;
			if($testmode)
				echo mysql_error();
			exit;
		}
	}
	else
		$dbinited=true;
}

if(!isset($cookiedomain) || !$cookiedomain)
{
	if($new_global_handling)
		$myhost=$_SERVER["HTTP_HOST"];
	else
		$myhost=$HTTP_SERVER_VARS["HTTP_HOST"];
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
if($new_global_handling)
{
	$act_script_url=$_SERVER["PHP_SELF"];
}
else
{
	$act_script_url=$PHP_SELF;
}
if(phpversion() < '4.1.0')
{
	$_POST    = &$HTTP_POST_VARS;
	$_GET     = &$HTTP_GET_VARS;
	$_COOKIE  = &$HTTP_COOKIE_VARS;
	$_REQUEST = array_merge($HTTP_COOKIE_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS);
}
while( list($var, $param) = @each($_POST) )
{
	if(!isset($$var) || $ovwglobals)
	{
		if((substr($var,0,4)!="url_") && (substr($var,0,5)!="path_") && ($var!="simpnews_fullurl"))
			$$var=$_POST[$var];
	}
	if(!get_magic_quotes_gpc())
		$$var=addslashes($$var);
}
while( list($var, $param) = @each($_GET) )
{
	if(!isset($$var) || $ovwglobals)
	{
		if((substr($var,0,4)!="url_") && (substr($var,0,5)!="path_") && ($var!="simpnews_fullurl"))
			$$var=$_GET[$var];
	}
	if(!get_magic_quotes_gpc())
		$$var=addslashes($$var);
}
if(!isset($REMOTE_ADDR))
{
	if($new_global_handling)
		@$REMOTE_ADDR=$_SERVER["REMOTE_ADDR"];
}
if(!isset($HTTP_USER_AGENT))
{
	if($new_global_handling)
		@$HTTP_USER_AGENT=$_SERVER["HTTP_USER_AGENT"];
}
if(!isset($REMOTE_USER))
{
	if($new_global_handling)
		@$REMOTE_USER=$_SERVER["REMOTE_USER"];
}
if(!isset($moppel))
	$moppel="abcdefg";
if(substr($url_simpnews,-1)=="/")
	$url_simpnews=substr($url_simpnews,0,-1);
if(substr($simpnews_fullurl,-1)!="/")
	$simpnews_fullurl=$simpnews_fullurl."/";
if(substr($url_gfx,-1)=="/")
	$url_gfx=substr($url_gfx,0,-1);
if(substr($url_emoticons,-1)=="/")
	$url_emoticons=substr($url_emoticons,0,-1);
if(substr($url_icons,-1)=="/")
	$url_icons=substr($url_icons,0,-1);
if(substr($url_inline_gfx,-1)=="/")
	$url_inline_gfx=substr($url_inline_gfx,0,-1);
if(substr($url_attach,-1)=="/")
	$url_attach=substr($url_attach,0,-1);
if(substr($path_simpnews,-1)=="/")
	$path_simpnews=substr($path_simpnews,0,-1);
if(substr($path_gfx,-1)=="/")
	$path_gfx=substr($path_gfx,0,-1);
if(substr($path_emoticons,-1)=="/")
	$path_emoticons=substr($path_emoticons,0,-1);
if(substr($path_icons,-1)=="/")
	$path_icons=substr($path_icons,0,-1);
if(substr($path_inline_gfx,-1)=="/")
	$path_inline_gfx=substr($path_inline_gfx,0,-1);
if(substr($path_logfiles,-1)=="/")
	$path_logfiles=substr($path_logfiles,0,-1);
if(substr($path_attach,-1)=="/")
	$path_attach=substr($path_attach,0,-1);
if(substr($thumbdir,-1)=="/")
	$thumbdir=substr($thumbdir,0,-1);
if((strlen($cookiepath)>1) && (substr($cookiepath,-1)=="/"))
	$cookiepath=substr($cookiepath,0,-1);
if(isset($$langvar))
{
	if(strlen($$langvar)>4)
		unset($$langvar);
}
if(isset($$langvar))
{
	if(!preg_match('/^[a-zA-Z0-9]{1,4}$/',$$langvar))
		unset($$langvar);
}
require_once($path_simpnews.'/includes/timezones.inc');
?>