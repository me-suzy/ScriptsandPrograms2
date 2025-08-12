<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!isset($nomenu))
	$nomenu=false;
if(!isset($admaltlayout))
	$admaltlayout=0;
if(!isset($page))
	$page="";
if(!isset($bbcbuttons))
	$bbcbuttons=false;
if(!isset($colorchooser))
	$colorchooser=false;
$usenewmenu=false;
$tmpsql="show tables like '".$tableprefix."_settings'";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database. ".mysql_error());
if(mysql_num_rows($tmpresult)<1)
	die("$l_tablesnotexisting");
if(!$noseccheck)
{
	$stop_now=0;
	$stopmsg="";
	if(file_exists("mkconfig.php"))
	{
		$stop_now=1;
		$msg=str_replace("{file}","mkconfig.php",$l_remove_file);
		$stopmsg.="<li>$msg";
	}
	if(file_exists("fill_emoticons.php"))
	{
		$stop_now=1;
		$msg=str_replace("{file}","fill_emoticons.php",$l_remove_file);
		$stopmsg.="<li>$msg";
	}
	if(file_exists("fill_icons.php"))
	{
		$stop_now=1;
		$msg=str_replace("{file}","fill_icons.php",$l_remove_file);
		$stopmsg.="<li>$msg";
	}
	if(file_exists("fill_freemailer.php"))
	{
		$stop_now=1;
		$msg=str_replace("{file}","fill_freemailer.php",$l_remove_file);
		$stopmsg.="<li>$msg";
	}
	if(file_exists("install.php"))
	{
		$stop_now=1;
		$msg=str_replace("{file}","install.php",$l_remove_file);
		$stopmsg.="<li>$msg";
	}
	$dir = opendir("./");
	while ($file = readdir($dir))
	{
		if (ereg("^upgrade_", $file))
		{
			$stop_now=1;
			$msg=str_replace("{file}",$file,$l_remove_file);
			$stopmsg.="<li>$msg";
		}
	}
	if(@fopen("../config.php", "a"))
	{
		$stop_now=1;
		$stopmsg.="<li>$l_config_writeable";
	}
	if($stop_now==1)
		die("<ul>".$stopmsg."</ul>");
}
require_once('../functions.php');
require_once('./functions.php');
if(is_leacher($HTTP_USER_AGENT))
{
	header("HTTP/1.0 403 Forbidden");
	exit;
}
if($admoldhdr)
{
	header('Pragma: no-cache');
	header('Expires: 0');
}
else
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
}
if(!isset($redirect))
{
	// Page to redirect after login
	if($iis_workaround)
	{
		if($new_global_handling)
			$redirect=$act_script_url . "?" .$_SERVER['QUERY_STRING'];
		else
			$redirect = $act_script_url . "?" .$HTTP_SERVER_VARS['QUERY_STRING'];
	}
	else if($new_global_handling)
		$redirect=$_SERVER["REQUEST_URI"];
	else
		$redirect=$REQUEST_URI;
	if(!$redirect)
		$redirect=$url_simpnews."/admin/index.php?$langvar=$act_lang";
}
$user_loggedin=0;
$url_sessid=0;
$userdata = Array();
$banreason="";
if(isset($do_login))
{
	$myusername=addslashes(strtolower($username));
	$result=do_login($myusername,$userpw,$db);
	if($result==22)
	{
		$pagetitle="Administration";
		include_once("./includes/html_head.inc");
?>
</head>
<body>
<table width="80%" align="CENTER" valign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" class="prognamerow"><h1>SimpNews v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$simpnewssitedesc ($simpnewssitename)"?></h4></td></tr>
<tr><td align="CENTER" class="pagetitlerow"><h2><?php echo $page_title?></h2></td></tr>
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
		$pagetitle="Administration";
		include_once("./includes/html_head.inc");
?>
</head>
<body>
<table width="80%" align="CENTER" valign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" class="prognamerow"><h1>SimpNews v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$simpnewssitedesc ($simpnewssitename)"?></h4></td></tr>
<tr><td align="CENTER" class="pagetitlerow"><h2><?php echo $page_title?></h2></td></tr>
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
	if(($result!=1) && ($result!=4711))
	{
		$pagetitle=$l_loginpage;
		include_once("./includes/html_head.inc");
?>
</head>
<body>
<table width="80%" align="CENTER" valign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" class="prognamerow"><h1>SimpNews v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$simpnewssitedesc ($simpnewssitename)"?></h4></td></tr>
<tr><td align="CENTER" class="pagetitlerow"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2">
<?php echo $l_loginerror?></td></tr>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="sninput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="sninput" type="password" name="userpw" size="40" maxlength="40"></td></tr>
<input type="hidden" name="do_login" value="1">
<input type="hidden" name="redirect" value="<?php echo $redirect?>">
<tr class="actionrow"><td align="center" colspan="2"><input class="snbutton" type="submit" value="<?php echo $l_login?>"></td></tr>
<?php
if($enablerecoverpw && !$enable_htaccess)
{
?>
<tr class="actionrow"><td align="center" colspan="2"><a href="pwlost.php?<?php echo "$langvar=$act_lang"?>"><?php echo $l_pwlost?></td></tr>
<?php
}
?>
</form></table></td></tr></table>
<?php
	echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
	echo "</body></html>";
	exit;
	}
	else
	{
		if($result==4711)
			$redirect="changepw.php?$langvar=$act_lang";
		if($userdata["rights"]>0)
		{
			$tmpsql="select * from ".$tableprefix."_globalmsg where added>='".$userdata["lastlogin"]."' and lang='$act_lang'";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Could not connect to the database. (globalmsg)");
			if(mysql_num_rows($tmpresult)>0)
				$redirect="showmsgs.php?$langvar=$act_lang&forward=".urlencode($redirect);
		}
		if($sessid_url)
			$redirect=do_url_session($redirect);
		echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirect\">";
		exit;
	}
}
if($enable_htaccess)
{
	if(isbanned(get_userip(),$db))
	{
		$pagetitle="Administration";
		include_once("./includes/html_head.inc");
?>
</head>
<body>
<table width="80%" align="CENTER" valign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" class="prognamerow"><h1>SimpNews v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$simpnewssitedesc ($simpnewssitename)"?></h4></td></tr>
<tr><td align="CENTER" class="pagetitlerow"><h2><?php echo $page_title?></h2></td></tr>
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
	$username=$REMOTE_USER;
	$myusername=addslashes(strtolower($username));
	$sql = "select * from ".$tableprefix."_users where username='$myusername'";
	if(!$result = mysql_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database");
	if (!$myrow = mysql_fetch_array($result))
	{
	    die("<tr bgcolor=\"#cccccc\"><td>User not defined for SimpNews");
	}
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
	$userid="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$sesscookiename]))
		{
			$sessid = $_COOKIE[$sesscookiename];
			$userid = get_userid_from_session($sessid, $sesscookietime, get_userip(), $db);
		}
	}
	else
	{
		if(isset($_COOKIE[$sesscookiename])) {
			$sessid = $_COOKIE[$sesscookiename];
			$userid = get_userid_from_session($sessid, $sesscookietime, get_userip(), $db);
		}
	}
	if ($userid) {
	   $user_loggedin = 1;
	   update_session($sessid, $db);
	   $userdata = get_userdata_by_id($userid, $db);
	   $userdata["lastlogin"]=get_lastlogin_from_session($sessid, $sesscookietime, get_userip(), $db);
	}
}
if($user_loggedin==0)
	$pagetitle=$l_loginpage;
else
{
	$pagetitle=$page_title;
	if(isset($dostorefilter))
	{
		include('./includes/store_filter.inc');
	}
}
include_once("./includes/html_head.inc");
$usedlayoutpage=0;
if($user_loggedin!=0)
{
	if($userdata["rights"]<2)
		$userdata["addoptions"]=0;
	$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.");
	if($myrow = mysql_fetch_array($result))
	{
		$subscriptionsendmode=$myrow["subscriptionsendmode"];
		$enablesubscriptions=$myrow["enablesubscriptions"];
		$subject=$myrow["subject"];
		$simpnewsmail=$myrow["simpnewsmail"];
		$simpnewsmailname=$myrow["simpnewsmailname"];
		$usemenubar=$myrow["usemenubar"];
		$servertimezone=$myrow["servertimezone"];
		$displaytimezone=$myrow["displaytimezone"];
		$admrestrict=$myrow["admrestrict"];
		$newsletternoicons=$myrow["newsletternoicons"];
		$admonlyentryheadings=$myrow["admonlyentryheadings"];
		$admentrychars=$myrow["admentrychars"];
		$admdelconfirm=$myrow["admdelconfirm"];
		$mailattach=$myrow["mailattach"];
		$evnewsletterinclude=$myrow["evnewsletterinclude"];
		$msendlimit=$myrow["msendlimit"];
		$sendnewsdelay=$myrow["sendnewsdelay"];
		$senddelayinterval=$myrow["senddelayinterval"];
		$showsendprogress=$myrow["showsendprogress"];
		$sendprogressautohide=$myrow["sendprogressautohide"];
		$admepp=$myrow["admepp"];
		$secsettings=$myrow["secsettings"];
		$bbcimgdefalign=$myrow["bbcimgdefalign"];
		$newsletterattachinlinepix=$myrow["newsletterattachinlinepix"];
		$subscriptionsendmode=$myrow["subscriptionsendmode"];
		$comments_allowed=$myrow["allowcomments"];
		$admstorefilter=$myrow["admstorefilter"];
		$allowsubscriptions=$myrow["enablesubscriptions"];
		$yearrange=$myrow["yearrange"];
		$minviews=$myrow["minviews"];
		$enablerating=$myrow["enablerating"];
		$rss_enable=$myrow["rss_enable"];
		$wap_enable=$myrow["wap_enable"];
		$emaillog=$myrow["emaillog"];
		$emailerrordie=$myrow["emailerrordie"];
		$dosearchlog=$myrow["dosearchlog"];
		$admaltlayout=$myrow["admaltlayout"];
		$newsletterlinking=$myrow["newsletterlinking"];
		$mailmaxlinelength=$myrow["mailmaxlinelength"];
		$loginlimit=$myrow["loginlimit"];
		$admaltprv=$myrow["admaltprv"];
		if($admaltlayout==1)
		{
			if(is_konqueror() || is_opera() || is_ns6() || is_ns4() || is_msie3())
				$usedlayoutpage=2;
			else
				$usedlayoutpage=1;
		}
	}
	else
	{
		$usemenubar=0;
		$servertimezone=0;
		$displaytimezone=0;
		$admrestrict=0;
		$newsletternoicons=1;
		$admonlyentryheadings=0;
		$admentrychars=20;
		$admdelconfirm=0;
		$mailattach=0;
		$evnewsletterinclude=0;
		$msendlimit=30;
		$sendnewsdelay=0;
		$senddelayinterval=1;
		$showsendprogress=1;
		$admepp=0;
		$secsettings=0;
		$bbcimgdefalign="center";
		$newsletterattachinlinepix=0;
		$subscriptionsendmode=0;
		$comments_allowed=1;
		$admstorefilter=1;
		$allowsubscriptions=0;
		$yearrange=10;
		$minviews=0;
		$admaltlayout=0;
		$enablerating=0;
		$sendprogressautohide=1;
		$subscriptionsendmode=0;
		$enablesubscriptions=0;
		$subject="News";
		$simpnewsmail="simpnews@foo.bar";
		$simpnewsmailname="SimpNews";
		$rss_enable=0;
		$wap_enable=0;
		$emaillog=0;
		$dosearchlog=0;
		$emailerrordie=1;
		$newsletterlinking=0;
		$mailmaxlinelength=998;
		$loginlimit=0;
		$admaltprv=0;
	}
	if($dosearchlog>0)
		$searchlogaccess=3;
	else
		$searchlogaccess=2;
	if($emaillog>0)
		$emaillogaccess=3;
	else
		$emaillogaccess=4;
	if($emaillogaccess<$searchlogaccess)
		$logaccess=$emaillogacces;
	else
		$logaccess=$searchlogaccess;
	switch($usedlayoutpage)
	{
		case 1:
			$layoutlink="layout2.php?$langvar=$act_lang";
			break;
		case 2:
			$layoutlink="layout2b.php?$langvar=$act_lang";
			break;
		default:
			$layoutlink="layout.php?$langvar=$act_lang";
			break;
	}
	if(bittst($secsettings,BIT_6))
		$posterlevel=2;
	else
		$posterlevel=3;
	if(bittst($secsettings,BIT_8))
		$pnlevel=2;
	else
		$pnlevel=3;
	if(bittst($secsettings,BIT_9))
		$pevlevel=2;
	else
		$pevlevel=3;
	if($allowsubscriptions==1)
	{
		$sublevel=4;
		$nllevel=4;
		if($subscriptionsendmode==0)
		{
			$nllevel=5;
			if($userdata["rights"]<4)
			{
				$sql="select * from ".$tableprefix."_newsletteradmins where usernr=".$userdata["usernr"];
				if(!$result=mysql_query($sql, $db))
					die("Unable to connect to database.");
				if(mysql_num_rows($result)>0)
					$sublevel=$userdata["rights"];
				else
					$sublevel=$userdata["rights"]+1;
			}
		}
		else if($userdata["rights"]>1)
		{
			$nllevel=4;
			if($userdata["rights"]<4)
			{
				$sql="select * from ".$tableprefix."_newsletteradmins where usernr=".$userdata["usernr"];
   				if(!$result=mysql_query($sql, $db))
					die("Unable to connect to database.");
				if(mysql_num_rows($result)>0)
					$nllevel=$userdata["rights"];
				else
					$nllevel=$userdata["rights"]+1;
			}
			$sublevel=$nllevel;
		}
	}
	else
	{
		$nllevel=5;
		$sublevel=5;
	}
	if(bittst($secsettings,BIT_11))
		$nproplevel=2;
	else
		$nproplevel=3;
	if(bittst($secsettings,BIT_12))
		$evproplevel=2;
	else
		$evproplevel=3;
	if(bittst($secsettings,BIT_13))
		$layoutlevel=2;
	else
		$layoutlevel=3;
	if(bittst($secsettings,BIT_14))
	{
		if(!bittst($secsettings,BIT_3))
			$anlevel=3;
		else
			$anlevel=2;
	}
	else
		$anlevel=1;
	if($userdata["rights"]>2)
		$attachlevel=3;
	else if(bittst($userdata["addoptions"],BIT_7))
		$attachlevel=2;
	else
		$attachlevel=3;
	if(bittst($secsettings,BIT_20))
		$importlevel=2;
	else
		$importlevel=3;
	include_once("./includes/js/global.inc");
	if(is_ns6() || is_ns4() || is_konqueror())
	{
		require_once("./menus.php");
?>
<script type="text/javascript" language="javascript">
<!--
// constants
var initX       = 95; // x-coordinate of top left corner of dropdown menu
var initY       = 93; // y-coordinate of top left corner of dropdown menu
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
			echo "95, // the width of current menu list\n";
			echo "-1, // x coordinate (absolute) of left corner of this menu list, -1 if the coordinate is defined from parent x-coordinate\n";
			echo "-1, // y coordinate (absolute) of left corner of this menu list, -1 if the coordinate is defined from parent y-coordinate\n";
			echo "new Array (";
			for($j=1;$j<count($l_menus[$i]);$j++)
			{
				if($l_menus[$i][$j]["level"]<=$userdata["rights"])
				{
					if($j>1)
						echo ",\n";
					echo "'".$l_menus[$i][$j]["entry"]."', '".do_url_session($l_menus[$i][$j]["url"])."'";
				}
			}
			echo "\n));\n";
		}
?>
// -->
</script>
<script language="JavaScript" type="text/javascript" src="js/menu.js"></script>
<?php
	}
	else
	{
		$usenewmenu=true;
		require_once("./menus2.php");
?>
<script language="JavaScript1.2" type="text/javascript" src="js/coolmenus4.js">
<!--
/*****************************************************************************
This site uses the coolMenus. You can get it for your own site by
going to http://www.dhtmlcentral.com/projects/coolmenus
******************************************************************************/
// -->
</script>
<script language="JavaScript1.2" type="text/javascript" src="js/cm_addins.js">
</script>
<?php
			echo "<script type=\"text/JavaScript\" language=\"JavaScript\">\n";
			echo "<!--\n";
			echo "function findPos(num)\n";
			echo "{\n";
			echo "	if(bw.ns4)\n";
			echo "	{	//Netscape 4\n";
			echo "		x = document.layers[\"layerMenu\"+num].pageX;\n";
    		echo "		y = document.layers[\"layerMenu\"+num].pageY;\n";
			echo "	}\n";
			echo "	else\n";
			echo "	{	//other browsers\n";
			echo "		x=0;\n";
			echo "		y=0;\n";
			echo "		var el,temp;\n";
    		echo "		el = bw.ie4?document.all[\"divMenu\"+num]:document.getElementById(\"divMenu\"+num);\n";
    		echo "		if(el.offsetParent)\n";
    		echo "		{\n";
			echo "			temp = el;\n";
			echo "			while(temp.offsetParent)\n";
			echo "			{	//Looping parent elements to get the offset of them as well\n";
			echo "				temp=temp.offsetParent;\n";
			echo "				x+=temp.offsetLeft;\n";
			echo "				y+=temp.offsetTop;\n";
			echo "			}\n";
			echo "		}\n";
			echo "		x+=el.offsetLeft;\n";
			echo "		y+=el.offsetTop;\n";
			echo "	}\n";
			echo "	//Returning the x and y as an array\n";
			echo "	return [x,y];\n";
			echo "}\n";
			echo "function placeElements(){\n";
			for($i=0;$i<count($l_menus);$i++)
			{
				if($l_menus[$i][0]["level"]<=$userdata["rights"])
				{
		  			echo "	pos = findPos($i);\n";
		  			echo "	oCMenu.m[\"top$i\"].b.moveIt(pos[0],pos[1]);\n";
				}
			}
		  	echo "	oCMenu.fromTop = pos[1];\n";
			echo "}\n";
			echo "// -->\n";
			echo "</script>\n";
	}
	if($bbcbuttons)
		include_once("./includes/js/bbcode_js.inc");
	if($colorchooser)
		include_once("./includes/js/color_chooser.inc");
}
if($page)
{
	if(file_exists('./includes/js/'.$page.'.inc'))
		include_once('./includes/js/'.$page.'.inc');
}
?>
</head>
<?php
if($page=="layout2")
	echo "<body onload=\"tabInit()\">";
else
	echo "<body>";
?>
<table width="80%" align="CENTER" valign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" class="prognamerow"><h1>SimpNews v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$simpnewssitedesc ($simpnewssitename)"?></h4></td></tr>
<tr><td align="CENTER" class="pagetitlerow"><h2><?php echo $page_title?></h2></td></tr>
</table>
<?php
if($user_loggedin==0)
{
	if(isbanned(get_userip(),$db))
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td colspan="2" align="center"><b><?php echo $l_ipbanned?></b></td></tr>
<?php
		if($banreason)
		{
?>
<tr class="displayrow"><td align="right" width="20%"><?php echo $l_reason?>:</td>
<td align="left" width="80%"><?php echo $banreason?></td></tr>
<?php
		}
?>
</table></td></tr></table></body></html>
<?php
		exit;
	}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2">
<?php echo $l_notloggedin?></td></tr>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="logid" value="<?php echo time()?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="sninput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="sninput" type="password" name="userpw" size="40" maxlength="40"></td></tr>
<input type="hidden" name="do_login" value="1">
<input type="hidden" name="redirect" value="<?php echo $redirect?>">
<tr class="actionrow"><td align="center" colspan="2"><input class="snbutton" type="submit" value="<?php echo $l_login?>"></td></tr>
<?php
if($enablerecoverpw && !$enable_htaccess)
{
?>
<tr class="actionrow"><td align="center" colspan="2"><a href="pwlost.php?<?php echo "$langvar=$act_lang"?>"><?php echo $l_pwlost?></td></tr>
<?php
}
?>
</form></table></td></tr></table>
<?php
	echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
	exit;
}
else
{
	$shutdown=0;
	$act_usernr=$userdata["usernr"];
	$admin_rights=$userdata["rights"];
	$sql = "select * from ".$tableprefix."_misc";
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database (".$tableprefix."_misc).");
	if ($temprow = mysql_fetch_array($result))
	{
		if(($temprow["shutdown"]>0) && ($admin_rights<4))
		{
			echo "<div align=\"center\">";
			$shutdowntext=stripslashes($temprow["shutdowntext"]);
			$shutdowntext = undo_htmlspecialchars($shutdowntext);
			if($shutdowntext)
				echo $shutdowntext;
			else
				echo $l_sysisshutdown;
			echo "</div>";
			$shutdown=1;
			include('./trailer.php');
			exit;
		}
	}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	$nummenucols=0;
	if($usemenubar==1)
	{
		if(!is_opera() && !is_ns4() && !is_gecko() && !is_msie())
		{
			echo "<tr class=\"menurow\">";
			for($i=0;$i<count($l_menus);$i++)
			{
				if($l_menus[$i][0]["level"]<=$userdata["rights"])
				{
					echo "<td ";
					if($i==0)
						echo "name=\"menucolumn\" ";
					echo "align=\"center\" valign=\"middle\" width=\"90\" height=\"20\">";
					echo "<a href=\"".do_url_session($l_menus[$i][0]["url"])."\" ";
					echo "onMouseOver = \"enterTopItem ($i);\" onMouseOut = \"exitTopItem ($i);\" class=\"topMenu\">".$l_menus[$i][0]["entry"]."</a></td>";
					$nummenucols++;
				}
			}
		}
		else
		{
			echo "<tr class=\"menurow\">";
			for($i=0;$i<count($l_menus);$i++)
			{
				if($l_menus[$i][0]["level"]<=$userdata["rights"])
				{
  					echo "<td width=\"11%\">";
  					echo "<ilayer id=\"layerMenu$i\"><div id=\"divMenu$i\">";
    				echo "<img src=\"gfx/space.gif\" width=\"6\" height=\"25\" alt=\"\" border=\"0\">";
  					echo "</div></ilayer></td>";
  					$nummenucols++;
  				}
			}
			for($j=$nummenucols;$j<count($l_menus);$j++)
			{
				echo "<td width=\"11%\">";
   				echo "<img src=\"gfx/space.gif\" width=\"6\" height=\"25\" alt=\"\" border=\"0\">";
   				echo "</td>";
   			}
		}
	}
	else
		echo "<tr bgcolor=\"#C0C0C0\">";
	echo "</tr>";
	if(($userdata["rights"]>2)||($shutdown<1))
	{
		echo "<tr class=\"actionrow\"><td align=\"center\"";
		if($nummenucols>0)
			echo " colspan=\"".count($l_menus)."\"";
		echo ">";
		echo "<a href=\"".do_url_session("index.php?$langvar=$act_lang")."\">$l_mainmenu</a>";
		echo "</td></tr>";
	}
	echo "</table></td></tr></table>";
}
?>