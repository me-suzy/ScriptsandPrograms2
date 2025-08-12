<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
// Set the error reporting to a sane value:
if(!$testmode)
{
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	if(!isset($noseccheck))
		$noseccheck=false;
}
else
	$noseccheck=true;
$err_query      = '<br><br><br><br><br><font size=+1>An Error Occured</font><hr>Unable to connect'.
		' to the database server.<BR>Please try again later.<BR>';  // Database, or querry error
if(isset($dosafephp) && ($dosafephp))
{
	require_once($path_faqe.'/includes/safephp.inc');
	safe_php();
}
$faqeversion ="4.09";
$offlistversion="4.07";
$catlistversion="4.07";
$kbcatlistversion="4.07";
$kbofflistversion="4.07";
$inlinegfxlistversion="4.07";
$backup_version="4.09";
$restore_version="4.09";
require_once($path_faqe."/includes/const.inc");
$altlinkmethod=0;
if(!isset($no_rgcheck))
	$no_rgcheck=false;
if(!isset($dodebug))
	$dodebug=false;
if(!isset($upload_hash))
	$upload_hash = md5($copyright_note);
$avail_mimetypes = array("text/plain","application/octetstream");
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

switch($dbengine)
{
	case 1:
		require_once($path_faqe."/includes/db_mysql.inc");
		break;
	default:
		die("Unsupported DB engine ($dbengine) defined");
}
$db=faqe_db_connect($dbhost,$dbuser,$dbpasswd,$dbname,$err_query);

if(!isset($cookiedomain))
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

if(phpversion() < '4.1.0')
{
	$_POST    = &$HTTP_POST_VARS;
	$_GET     = &$HTTP_GET_VARS;
	$_COOKIE  = &$HTTP_COOKIE_VARS;
	$_REQUEST = array_merge($HTTP_COOKIE_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS);
}

if($new_global_handling)
	$act_script_url=$_SERVER["PHP_SELF"];
else
	$act_script_url=$PHP_SELF;

while( list($var, $param) = @each($_POST) )
{
	if(!isset($$var))
	{
		if((substr($var,0,4)!="url_") && (substr($var,0,5)!="path_"))
			$$var=$_POST[$var];
	}
	if(!get_magic_quotes_gpc())
		$$var=addslashes($$var);
}
while( list($var, $param) = @each($_GET) )
{
	if(!isset($$var))
	{
		if((substr($var,0,4)!="url_") && (substr($var,0,5)!="path_"))
			$$var=$_GET[$var];
	}
	if(!get_magic_quotes_gpc())
		$$var=addslashes($$var);
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
if(!isset($REMOTE_USER))
{
	if($new_global_handling)
		@$REMOTE_USER=$_SERVER["REMOTE_USER"];
}

if(substr($url_faqengine,-1)=="/")
	$url_faqengine=substr($url_faqengine,0,-1);
if(substr($url_gfx,-1)=="/")
	$url_gfx=substr($url_gfx,0,-1);
if(substr($path_faqe,-1)=="/")
	$path_faqe=substr($path_faqe,0,-1);
if(substr($path_gfx,-1)=="/")
	$path_gfx=substr($path_gfx,0,-1);
if(substr($path_logfiles,-1)=="/")
	$path_logfiles=substr($path_logfiles,0,-1);
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
require_once($path_faqe."/includes/timezones.inc");
?>