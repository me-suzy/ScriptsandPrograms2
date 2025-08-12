<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('../config.php');
include('../functions.php');
if(!$insafemode)
	@set_time_limit($longrunner);
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include('./language/lang_'.$act_lang.'.php');
require('./auth.php');
$crlf="\n";
$url_sessid=0;
$user_loggedin=0;
$userdata=Array();
if($enable_htaccess)
{
	if(isbanned(get_user_ip(),$db))
	{
?>
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<title>FAQEngine - Administration</title>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$faqsitedesc ($faqsitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td align="CENTER"><font size="+2"><?php echo $page_title?></font></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td colspan="2" align="center"><b><?php echo $l_ipbanned?></b></td></tr>
<tr class="displayrow"><td align="right" width="20%"><?php echo $l_reason?>:</td>
<td align="left" width="80%"><?php echo $banreason?></td></tr>
</table></td></tr></table></body></html>
<?php
	}
	$username=$REMOTE_USER;
	$myusername=addslashes(strtolower($username));
	$sql = "select * from ".$tableprefix."_admins where username='$myusername'";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Unable to connect to database");
	if (!$myrow = faqe_db_fetch_array($result))
		die("<tr class=\"errorrow\"><td>$l_undefuser");
	$userid=$myrow["usernr"];
	$user_loggedin=1;
    $userdata = get_userdata_by_id($userid, $db);
}
else if($sessid_url)
{
	if(isset($$sesscookiename))
	{
		$url_sessid=$$sesscookiename;
		$userid = get_userid_from_session($url_sessid, $sesscookietime, get_user_ip(), $db);
		if ($userid) {
		   $user_loggedin = 1;
		   update_session($url_sessid, $db);
		   $userdata = get_userdata_by_id($userid, $db);
		   $userdata["lastlogin"]=get_lastlogin_from_session($url_sessid, $sesscookietime, get_user_ip(), $db);
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
			$userid = get_userid_from_session($sessid, $sesscookietime, get_user_ip(), $db);
		}
	}
	else
	{
		if(isset($_COOKIE[$sesscookiename])) {
			$sessid = $_COOKIE[$sesscookiename];
			$userid = get_userid_from_session($sessid, $sesscookietime, get_user_ip(), $db);
		}
	}
	if ($userid)
	{
	   $user_loggedin = 1;
	   update_session($sessid, $db);
	   $userdata = get_userdata_by_id($userid, $db);
	   $userdata["lastlogin"]=get_lastlogin_from_session($sessid, $sesscookietime, get_user_ip(), $db);
	}
}
if($user_loggedin==0)
{
	echo "<div align=\"center\">$l_notloggedin2</div>";
	echo "<div align=\"center\">";
	echo "<a href=\"login.php?$langvar=$act_lang\">$l_loginpage</a>";
	die ("</div>");
}
else
{
	$admin_rights=$userdata["rights"];
}
if($admin_rights<2)
{
	die($l_functionotallowed);
}
$dump_buffer="{".$upload_hash."}".$crlf;
$dump_buffer.="{filedesc}".$crlf;
$dump_buffer.="faqdatlist".$crlf;
$dump_buffer.=$catlistversion.$crlf;
$dump_buffer.=date("Y-m-d H:i:s").$crlf;
$dump_buffer.="{/filedesc}".$crlf;
$dump_buffer.="{faqdatlist}".$crlf;
$sql = "select cat.*, prog.programmname, prog.language from ".$tableprefix."_category cat, ".$tableprefix."_programm prog where prog.prognr=cat.programm order by cat.catnr asc";
if(!$result = faqe_db_query($sql, $db))
	db_die("Could not connect to the database.");
if($myrow=faqe_db_fetch_array($result))
{
	$dump_buffer.="{cats}".$crlf;
	do{
		$dump_buffer.=$myrow["catnr"].$crlf;
		$dump_buffer.=$myrow["categoryname"]." (".$myrow["programmname"]." [".$myrow["language"]."])".$crlf;
	}while($myrow=faqe_db_fetch_array($result));
	$dump_buffer.="{/cats}".$crlf;
}
$sql = "select subcat.*, cat.categoryname as maincat, cat.catnr as maincatnr, prog.programmname, prog.language from ".$tableprefix."_subcategory subcat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where prog.prognr=cat.programm and cat.catnr=subcat.category order by subcat.catnr asc";
if(!$result = faqe_db_query($sql, $db))
	db_die("Could not connect to the database.");
if($myrow=faqe_db_fetch_array($result))
{
	$dump_buffer.="{subcats}".$crlf;
	do{
		$dump_buffer.=$myrow["catnr"].$crlf;
		$dump_buffer.=$myrow["maincatnr"].$crlf;
		$dump_buffer.=$myrow["categoryname"]." (".$myrow["maincat"]." [".$myrow["programmname"].", ".$myrow["language"]."])".$crlf;
	}while($myrow=faqe_db_fetch_array($result));
	$dump_buffer.="{/subcats}".$crlf;
}
$sql="select dat.faqnr, dat.heading, prog.programmname, prog.progid, prog.language, cat.categoryname, cat.catnr from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.category=cat.catnr and prog.prognr=cat.programm ";
$sql.="group by dat.faqnr order by prog.language asc";
$sql.=", prog.programmname asc, cat.categoryname asc, dat.heading asc";
if(!$result = faqe_db_query($sql, $db))
	db_die("Could not connect to the database.");
if($myrow=faqe_db_fetch_array($result))
{
	$dump_buffer.="{faqrefs}".$crlf;
	do{
		$dump_buffer.= $myrow["faqnr"]."|".$myrow["catnr"]."|".$myrow["progid"].$crlf;
		$dump_buffer.= "{".$myrow["faqnr"]."} ".stripslashes($myrow["heading"])." (".$myrow["categoryname"]." [".$myrow["programmname"]."|".$myrow["language"]."])".$crlf;
	}while($myrow=faqe_db_fetch_array($result));
	$dump_buffer.="{/faqrefs}".$crlf;
}

$sql="select dat.articlenr, dat.heading, dat.category, prog.programmname, prog.language, prog.progid from ".$tableprefix."_kb_articles dat, ".$tableprefix."_programm prog where dat.programm=prog.prognr order by prog.language, prog.displaypos, dat.displaypos";
if(!$result = faqe_db_query($sql, $db))
	db_die("Could not connect to the database.");
if($myrow=faqe_db_fetch_array($result))
{
	$dump_buffer.="{kbrefs}".$crlf;
	do{
		if($myrow["category"]!=0)
		{
			$tmpsql="select * from ".$tableprefix."_kb_cat where catnr=".$myrow["category"];
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				db_die("Could not connect to the database.");
			if($tmprow=faqe_db_fetch_array($tmpresult))
				$catname=$tmprow["catname"];
			else
				$catname=$l_none;
		}
		else
			$catname=$l_none;
		$dump_buffer.= $myrow["articlenr"]."|".$myrow["progid"].$crlf;
		$dump_buffer.= "{".$myrow["articlenr"]."} ".stripslashes($myrow["heading"])." (".$catname." [".$myrow["programmname"]."|".$myrow["language"]."])".$crlf;
	}while($myrow=faqe_db_fetch_array($result));
	$dump_buffer.="{/kbrefs}".$crlf;
}
$dump_buffer.="{/faqdatlist}".$crlf;
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
header("Content-Type: application/octetstream\n");
header("Content-Disposition: filename=\"faq_dat.fcl\"\n");
header("Content-Transfer-Encoding: binary\n");
header("Content-length: ".strlen($dump_buffer)."\n");
print($dump_buffer);
?>
