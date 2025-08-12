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
require_once('../functions.php');
require_once('./functions.php');
header('Pragma: no-cache');
header('Expires: 0');
if(!isset($page))
	$page="";
if(!isset($redirect))
{
	if($new_global_handling)
		$redirect=$_SERVER["REQUEST_URI"];
	else
		$redirect=$REQUEST_URI;
}
$user_loggedin=0;
$url_sessid=0;
$userdata = Array();
if(isset($do_login))
{
	$myusername=addslashes(strtolower($username));
	$result=do_login($myusername,$userpw,$db, &$banreason);
	if($result==22)
	{
?>
<html>
<head>
<title>ProgSys - Administration</title>
<link rel=stylesheet href=./psadm.css type=text/css>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td align="center"><a name="#top"></a><h1>ProgSys v<?php echo $version?></h1></td></tr>
<tr><td align="center" class="sitename"><h4><?php echo "$progsys_sitedesc ($progsys_sitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td align="CENTER"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td colspan="2" align="center"><?php echo $l_too_many_users?></td></tr>
</table></td></tr></table></body></html>
<?php
		exit;
	}
	if($result==-99)
	{
?>
<html>
<head>
<title>ProgSys - Administration</title>
<link rel=stylesheet href=./psadm.css type=text/css>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td align="center"><a name="#top"></a><h1>ProgSys v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$progsys_sitedesc ($progsys_sitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td align="CENTER"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td colspan="2" align="center"><b><?php echo $l_ipbanned?></b></td></tr>
<tr class="displayrow"><td align="right" width="20%"><?php echo $l_reason?>:</td>
<td align="left" width="80%"><?php echo $banreason?></td></tr>
</table></td></tr></table></body></html>
<?php
		exit;
	}
	if($result!=1)
	{
?>
<html>
<head>
<title>ProgSys - <?php echo $l_loginpage?></title>
<link rel=stylesheet href=./psadm.css type=text/css>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td align="center"><a name="#top"></a><h1>ProgSys v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$progsys_sitedesc ($progsys_sitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td align="CENTER"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2">
<?php echo $l_loginerror?></td></tr>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="psysinput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="psysinput" type="password" name="userpw" size="40" maxlength="40"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="psysbutton" type="submit" name="do_login" value="<?php echo $l_login?>"></td></tr>
</form></table></td></tr></table>
<?php
	echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
	exit;
	}
	else
	{
		if($sessid_url)
			$redirect=do_url_session($redirect);
		echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirect\">";
		exit;
	}
}
if($enable_htaccess)
{
	$username=$REMOTE_USER;
	$myusername=addslashes(strtolower($username));
	$sql = "select * from ".$tableprefix."_admins where username='$myusername'";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database ".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
	    die("<tr class=\"errorrow\"><td>User not defined for Progsys");
	$userid=$myrow["usernr"];
	$user_loggedin=1;
	$userdata = get_userdata_by_id($userid, $db);
}
else if($sessid_url)
{
	if(isset($$sesscookiename))
	{
		$url_sessid=$$sesscookiename;
		$userid = get_userid_from_session($url_sessid, $sesscookietime, get_userip(), $db);
		if ($userid)
		{
		   $user_loggedin = 1;
		   update_session($url_sessid, $db);
		   $userdata = get_userdata_by_id($userid, $db);
		   $userdata["lastlogin"]=get_lastlogin_from_session($url_sessid, $sesscookietime, get_userip(), $db);
		}
	}
}
else
{
	if(isset($_COOKIE[$sesscookiename]))
	{
		$sessid = $_COOKIE[$sesscookiename];
		$userid = get_userid_from_session($sessid, $sesscookietime, get_userip(), $db);
		if ($userid)
		{
			$user_loggedin = 1;
		   update_session($sessid, $db);
		   $userdata = get_userdata_by_id($userid, $db);
		   $userdata["lastlogin"]=get_lastlogin_from_session($sessid, $sesscookietime, get_userip(), $db);
		}
	}
}
if($user_loggedin==0)
	$page_title=$l_loginpage;
else if(isset($dostorefilter))
{
	include('./includes/store_filter.inc');
}
?>
<html>
<head>
<title>ProgSys - <?php echo $page_title?></title>
<?php
if($user_loggedin!=0)
{
	require_once("menus.php");
	if($page)
	{
		if(file_exists('./includes/js/'.$page.'.inc'))
			include_once('./includes/js/'.$page.'.inc');
	}
?>
<script type="text/javascript" language="javascript">
// constants
var initX       = 85; // x-coordinate of top left corner of dropdown menu
var initY       = 92; // y-coordinate of top left corner of dropdown menu
var backColor   = ''; // the background color of dropdown menu, set empty '' for transparent
var borderColor = 'black'; // the color of dropdown menu border
var borderSize  = '1'; // the width of dropdown menu border
var itemHeight  = 20;
var xOverlap    = 5;
var yOverlap    = 10;
//

// Don't change these parameters
var delay        = 500; /////
var menuElement  = new Array ();
var usedWidth    = 0;
var numOfMenus   = 0;
/// ----------------------------

var menuContent     = new Array ();

<?php
for($i=0;$i<count($l_menus);$i++)
{
echo "menuContent [$i] = new Array (\n";
echo "-1, // the id of parent menu, -1 if this is a first level menu\n";
echo "-1, // the number of line in parent menu, -1 if this is a first level menu\n";
echo "83, // the width of current menu list\n";
echo "-1, // x coordinate (absolute) of left corner of this menu list, -1 if the coordinate is defined from parent x-coordinate\n";
echo "-1, // y coordinate (absolute) of left corner of this menu list, -1 if the coordinate is defined from parent y-coordinate\n";
echo "new Array (";
for($j=1;$j<count($l_menus[$i]);$j++)
{
	if($l_menus[$i][$j]["level"]<=$userdata["rights"])
	{
		if($j>1)
			echo ",\n";
		$menuurl=do_url_session($l_menus[$i][$j]["url"]);
		echo "'".$l_menus[$i][$j]["entry"]."', '".$menuurl."'";
	}
}
echo "\n));\n";
}
?>
</script>
<script language="JavaScript" src="menu.js"></script>
<script type="text/javascript">
<!--
function program_maxconfirmtime()
{
	if(document.myform.nosubscriptionconfirm.checked == true)
		document.myform.maxconfirmtime.disabled=true;
	else
	{
		document.myform.maxconfirmtime.disabled=false;
		document.myform.maxconfirmtime.focus();
	}
	return;
}
function confirmDel(entrytxt, targetlocation)
{
	result = window.confirm('<?php echo undo_htmlentities($l_confirmdel)?> ('+entrytxt+')');
	if(result==true)
		window.location.href=targetlocation;
}
function doconfirm(entrytxt, targetlocation)
{
	result = window.confirm(entrytxt);
	if(result==true)
		window.location.href=targetlocation;
}
//-->
</script>
<link rel="stylesheet" href="psadm.css" type="text/css">
<?php
}
?>
<link rel="stylesheet" href="psadm.css" type="text/css">
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td align="center"><a name="#top"></a><h1>ProgSys v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$progsys_sitedesc ($progsys_sitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td align="CENTER"><h2><?php echo $page_title?></h2></td></tr>
<?php
if($user_loggedin==0)
{
?>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2">
<?php echo $l_notloggedin?></td></tr>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="psysinput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="psysinput" type="password" name="userpw" size="40" maxlength="40"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="psysbutton" type="submit" name="do_login" value="<?php echo $l_login?>"></td></tr>
</form></table></td></tr></table>
<?php
	echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
	exit;
}
else
{
	$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
	if(!$result = mysql_query($sql, $db)) {
	    die("Could not connect to the database.");
	}
	if ($myrow = mysql_fetch_array($result))
	{
		$enablespcode=$myrow["enablespcode"];
		$urlautoencode=$myrow["urlautoencode"];
		$server_timezone=$myrow["timezone"];
		$usemenubar=$myrow["usemenubar"];
		$admdelconfirm=$myrow["admdelconfirm"];
		$homepageurl=$myrow["homepageurl"];
		$homepagedesc=$myrow["homepagedesc"];
		$topfilter=$myrow["topfilter"];
		$admstorefilter=$myrow["admstorefilter"];
		$loginlimit=$myrow["loginlimit"];
	}
	else
	{
		$enablespcode=1;
		$urlautoencode=1;
		$sever_timezone=0;
		$usemenubar=0;
		$admdelconfirm=0;
		$homepageurl="";
		$homepagedesc="";
		$topfilter=0;
		$admstorefilter=0;
		$loginlimit=0;
	}
	$shutdown=0;
	$act_usernr=$userdata["usernr"];
	$admin_rights=$userdata["rights"];
	$sql = "select * from ".$tableprefix."_misc";
	if(!$result = mysql_query($sql, $db)) {
		die("Could not connect to the database (".$tableprefix."_misc).");
	}
	if ($temprow = mysql_fetch_array($result))
	{
		if(($temprow["shutdown"]>0) && ($admin_rights<4))
		{
			echo "<div align=\"center\">";
			$shutdowntext=stripslashes($temprow["shutdowntext"]);
			$shutdowntext = undo_htmlspecialchars($shutdowntext);
			echo $shutdowntext;
			echo "</div>";
			$shutdown=1;
			include('trailer.php');
			exit;
		}
	}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($usemenubar==1)
	{
		echo "<tr class=\"menurow\">";
		for($i=0;$i<count($l_menus);$i++)
		{
			if($l_menus[$i][0]["level"]<=$userdata["rights"])
			{
				$menuurl=do_url_session($l_menus[$i][0]["url"]);
				echo "<td align=\"center\" valign=\"middle\" width=\"80\" height=\"20\">";
				echo "<a href=\"".$menuurl."\" ";
				echo "onMouseOver = \"enterTopItem ($i);\" onMouseOut = \"exitTopItem ($i);\" class=\"topMenu\">".$l_menus[$i][0]["entry"]."</a></td>";
			}
		}
	}
	else
		echo "<tr bgcolor=\"#C0C0C0\">";
	$helpfile ="help/".$lang."/".basename($act_script_url);
	if(file_exists($helpfile))
		echo "<td align=\"right\"><font size=\"2\"><a href=\"".$helpfile."?lang=".$lang."\" target=\"_blank\">$l_help</a></td>";
	else
	{
		if($usemenubar==1)
			echo "<td>&nbsp;</td>";
	}
	echo "</tr></table></td></tr></table>";
}
?>
