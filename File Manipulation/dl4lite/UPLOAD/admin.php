<?php
/*********************************************************
 * Name: admin.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: Admin home page
 * Version: 4.0
 * Last edited: 22 January, 2004
 *********************************************************/

// Root path
define( 'ROOT_PATH', "./" );
define( 'INSTALL', 0 );
// Create our superglobal wotsit so we can save doing the same things over and over
class wotsit
{
	var $path = "";
	var $url = "";
	var $skinurl = "";
	var $cat_cache  = array();
	var $cats_saved = 0;
	var $image_cache = array();
    var $imgs_saved = 0;
	var $user_cache = array();
	var $error_log  = "";
	var $nav = "";
	var $userbar = "";
	var $links = "";
	var $lang = array();
	var $loaded_templates = array();
	var $skin_global;
	var $skin_wrapper;
}

$rwdInfo = new wotsit();

// Load config
$CONFIG = array();
require_once ROOT_PATH."/globalvars.php";

// Create helper globals because I'm too lazy to type $CONFIG["array"] all the time
$rwdInfo->path = $CONFIG['sitepath'];
$rwdInfo->url = $CONFIG['siteurl'];
$rwdInfo->skinurl = ROOT_PATH."/skins/admin/";

// Set warning level
error_reporting  (E_ERROR | E_WARNING | E_PARSE);
//error_reporting  (E_ALL);

// Load required libraries
require_once (ROOT_PATH."/functions/mysql.php");
require_once (ROOT_PATH."/functions/global_functions.php");
require_once (ROOT_PATH."/functions/users.php");
require_once (ROOT_PATH."/functions/lang.php");
require_once (ROOT_PATH."/functions/output.php");

// Our skin handler
$OUTPUT = new CDisplay();
// Global functions
$std    = new func();
// Get data from global arrays
$IN 	= $std->saveGlobals();

// Load the database
$dbinfo = array("sqlhost" => $CONFIG["sqlhost"],
		"sqlusername" => $CONFIG["sqlusername"],
		"sqlpassword" => $CONFIG["sqlpassword"],
		"sqldatabase" => $CONFIG["sqldatabase"],
		"sql_tbl_prefix" => $CONFIG["sqlprefix"]);

$DB = new mysql($dbinfo);

$langpref = $CONFIG['defaultLang'];
	
if ( !$IN["area"] )
	$area = "main";
else
	$area = $IN["area"];
include ROOT_PATH."/lang/".$langpref."/lang_ad_".$area.".php";
$lang_1 = $lang;
include ROOT_PATH."/lang/".$langpref."/lang_global.php";
$lang_2 = $lang;
include ROOT_PATH."/lang/".$langpref."/lang_warn.php";
$lang_3 = $lang;
include ROOT_PATH."/lang/".$langpref."/lang_error.php";
$lang_4 = $lang;
$rwdInfo->lang = array_merge($lang_1, $lang_2, $lang_3, $lang_4);

// Get the session ID
$sid = $IN['sid'];

// Check for old files in the temp folder
$dirpath = $CONFIG["sitepath"]."/temp/";
$dir_handle = @opendir($dirpath) or die("Unable to open $dirpath");
while($file = readdir($dir_handle)) 
{
	if ( $file != "." && $file != ".." && $file != "index.htm" )
	{
		$filetime = filectime($dirpath.$file);
		// If file is older than 12 hours old then remove
		if ( time() - $filetime > 43200 )
		{
			if ( !@unlink($dirpath.$file) )
				//$err_msg .= error("Could not remove file: ".$file);
				continue;
		}
	}
}

// Check if we are auto logging in
if ($IN["login"] != '1')
{
	// If there is no session ID then login
	if (!$sid)
	{
		login();
		return;
	}
	$hResult = $DB->query("SELECT * FROM dl_sessions WHERE sID = '$sid'");
	if(!$DB->affected_rows($hResult))
	{ 
		// No match for sid so login
		$std->error(GETLANG("er_sessExpired"));
		login();
		return;
	} 
	else
	{
		// Check session has not expired
		$myrow = $DB->fetch_row($hResult);
		$timenow = time();
		
		if (($timenow - $myrow["sTime"]) > ($CONFIG["session"] * 60) )
		{
			$DB->query("DELETE FROM dl_sessions WHERE sID = '$sid'");
			$std->warning(GETLANG("er_sessExpired"));
			login();
			return;
		}
		else
		{	
			// Session has not expired so update with current time as user is still active
			$time = time();
			$DB->query("UPDATE dl_sessions SET sTime='$time' WHERE sID = '$sid'");
			
			$aduser = new user();
			
   			// Check user exists. If not, log in.
   			if (!$aduser->adminLogin($sid))
   			{
   				login();
   				return;
   			}
   			// Check if this user should be here. If not, log in
   			if (!$aduser->isAdmin)
   			{
   				$std->error(GETLANG("er_adminAuth").$aduser->userlevel);
   				login();
   				return;
   			}
            
		}		
	}
}
// Otherwise the login button was clicked
else
{
	// Check if the information has been filled in 
	if($IN["username"] == '' || $IN["userpw"] == '') 
	{ 
		// No login information 
		$std->error(GETLANG("warn_missing"));
		login(); 
		return;
	} 
	else 
	{ 
		$aduser = new user();
		if ( !$aduser->errormsg )
        {
    		// Check user exists
    		if (!$aduser->do_login())
    		{
    			$std->error(GETLANG("er_nomatch"));
    			login();
    			return;
    		}
    		else
    		{
    			// Check if authorised to view this page
    			if (!$aduser->isAdmin)
    			{
    				$std->error(GETLANG("er_adminAuth").$aduser->userlevel);
    				login();
    				return;
    			}

    			srand(make_seed());
    			$session = md5(time() + $aduser->userid + rand());
    			// Update the user record
    			$time = time();
    			// Check if sid already present
    			$sr = $DB->query("SELECT * FROM dl_sessions WHERE id = '$aduser->userid'");
    			// if rows returned then update
    			if ( $DB->num_rows($sr) )
    				$sql = "UPDATE dl_sessions SET sID = '$session', sTime='$time' WHERE id = $aduser->userid";
    			else	// else add new row
    				$sql = "INSERT INTO dl_sessions ( id, sID, sTime ) VALUES ( '$aduser->userid', '$session', '$time' )";
    			$DB->query($sql);

    			$sid = $session;
    			build_frames();
    			return;

    		}
        }
        else
        {
			login();
			return;
		}
	}
}

if ( !empty($IN["area"]) )
{
	$DB->query("SELECT * FROM `dl_links` WHERE `approved`=0");
	$unapp = $DB->num_rows();
		
	if ( $IN["area"] == "nav" )
	{
		require ROOT_PATH."/functions/admin/ad_".$IN["area"].".php";
	}
	else
	{
		require ROOT_PATH."/functions/admin/ad_".$IN["area"].".php";
	}

	main_template();
}

// seed with microseconds
function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

function login()
{
	global $OUTPUT;
	
	$output = "";
	$output = admin_head("RW::Download ACP", "Login");
	
	$output .= "<center><form method='post' enctype='multipart/form-data' action='admin.php' target='_top'>";
	$output .= new_table(-1, "", "", "300");
		$output .= GETLANG("username").":";
		$output .= new_col();
		$output .= "<input type='text' name='username'>";
	$output .= new_row();
		$output .= GETLANG("password").":";
		$output .= new_col();
		$output .= "<input type='password' name='userpw'>";
	$output .= new_row();
		$output .= "&nbsp;";
		$output .= new_col();
		$output .= "<input type='hidden' name='login' value='1'>";
		$output .= "<input type='submit' name='submit' value='".GETLANG("login")."'>";
	$output .= end_table();
	$output .= "</form></center>";

	$output .= admin_foot();
	$OUTPUT->add_output($output);
	main_template();
}

function main_template()
{
	global $OUTPUT;

	$OUTPUT->print_output();
}

function build_frames()
{
	global $OUTPUT, $version, $sid;
	echo "<html>
		 <head><title>RW::Download ACP - Version $version</title></head>
		   <frameset cols='185, *' frameborder='no' border='0' framespacing='0'>
			<frame name='menu' noresize scrolling='auto' src='admin.php?sid=$sid&area=nav'>
			<frame name='body' noresize scrolling='auto' src='admin.php?sid=$sid&area=main'>
		   </frameset>
	   </html>";
	
}

function admin_head($title="RW::Download", $subtitle="Admin CP")
{
	global $rwdInfo;
	$output = "<div class='tableborder'>
	<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td class='top1'>&nbsp;$title </td>
  </tr>
  <tr>
    <td class='top2'><table width='100%'  border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td width='250' bgcolor='#333333' class='smallheadtext'>+ $subtitle </td>
        <td width='18'><img src='$rwdInfo->skinurl/images/smallhead.gif' width='18' height='12'></td>
        <td>&nbsp;</td>
      </tr>
    </table> </td>
  </tr>
  <tr>
    <td class='main_frame_bg'>";
	
	return $output;
}

function admin_foot()
{
	$output = "</td>
		  </tr>
		</table></div>";
	return $output;
}

// Nice hack to save modifying all the new_table calls I mad in the admin section
function new_table($colspan = -1, $class="", $tdclass="", $width="100%", $colwidth="", $padding=2)
{
	global $OUTPUT;
	$output = $OUTPUT->new_table($colspan, $class, $tdclass, $width, $colwidth, $padding);
	return $output;
}
function new_row($colspan = -1, $class="", $tdclass="", $width="")
{
	global $OUTPUT;
	$output = $OUTPUT->new_row($colspan, $class, $tdclass, $width);
	return $output;
}
function new_col($colspan = -1, $tdclass="")
{
	global $OUTPUT;
	$output = $OUTPUT->new_col($colspan, $tdclass);
	return $output;
}
function end_table()
{
	global $OUTPUT;
	$output = $OUTPUT->end_table();
	return $output;
}

?>